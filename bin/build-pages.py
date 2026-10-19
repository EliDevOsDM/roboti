"""Exporta las vistas públicas de PHP para GitHub Pages, con una BD de compilación aislada."""
from pathlib import Path
import argparse
import json
import os
import re
import shutil
import subprocess
import tempfile

root=Path(__file__).resolve().parents[1]
parser=argparse.ArgumentParser()
parser.add_argument('--base-path',default='/roboti')
options=parser.parse_args()
base=options.base_path.rstrip('/')
if base and not re.fullmatch(r'/[A-Za-z0-9_/-]+',base):
    raise SystemExit('La ruta base debe ser una ruta local como /roboti.')
output=root/'build/pages'
if output.exists():
    raise SystemExit('build/pages ya existe. Usa una carpeta de trabajo limpia para compilar de nuevo.')
output.mkdir(parents=True)
php=shutil.which('php')
if not php: raise SystemExit('Se requiere PHP con pdo_sqlite para compilar.')
args=[php]
extensions=Path(php).parent/'ext'
if (extensions/'php_pdo_sqlite.dll').exists():
    args+=['-d',f'extension_dir={extensions}','-d','extension=pdo_sqlite']
with tempfile.TemporaryDirectory(prefix='roboti-pages-') as directory:
    environment=dict(os.environ,DB_CONNECTION='sqlite',SQLITE_PATH=str(Path(directory)/'build.sqlite'),APP_URL=base,PAGES_BUILD='1',CONTACT_EMAIL='',CONTACT_WHATSAPP='')
    subprocess.run(args+['bin/init.php'],cwd=root,env=environment,check=True,capture_output=True)
    def render(mode,page='',query=''):
        result=subprocess.run(args+['bin/render-static.php',mode,page,query],cwd=root,env=environment,capture_output=True)
        if result.returncode: raise RuntimeError(result.stderr.decode('utf-8',errors='replace'))
        return result.stdout.decode('utf-8')
    records=json.loads(render('cards'))
    def save(page,name,query=''):
        body=render('page',page,query)
        script=f'<script src="{base}/assets/js/pages-preview.js" defer></script>'
        body=body.replace('</head>',script+'</head>')
        notice='<aside class="preview-note" aria-label="Vista previa">Vista previa de Roboti Academy · Consulta disponibilidad antes de inscribirte.</aside>'
        body=body.replace('</body>',notice+'</body>')
        destination=output/name
        destination.parent.mkdir(parents=True,exist_ok=True)
        destination.write_text(body,encoding='utf-8')
    for page in ['index','programas','metodologia','club','open-games','nosotros','contacto','privacidad']:
        save(page+'.php',page+'.html')
    for c in records:
        save('programa.php','programas/'+c['slug']+'.html','slug='+c['slug'])
    (output/'assets').mkdir(exist_ok=True)
    for name in ['css','js','fonts']:
        shutil.copytree(root/'public/assets'/name,output/'assets'/name)
    (output/'assets/images').mkdir()
    for image in (root/'public/assets/images').iterdir():
        if image.suffix.lower() in ['.webp','.svg']:
            shutil.copy2(image,output/'assets/images'/image.name)
    shutil.copytree(root/'public/reglamentos',output/'reglamentos')
    (output/'assets/courses.json').write_text(json.dumps(records,ensure_ascii=False),encoding='utf-8')
    (output/'.nojekyll').write_text('',encoding='utf-8')
    error=f'<!doctype html><html lang="es"><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Página no encontrada · Roboti</title><link rel="stylesheet" href="{base}/assets/css/site.css"><body><main class="wrap section"><h1>Sigamos explorando.</h1><p>Esta página no está disponible.</p><a class="button" href="{base}/index.html">Volver a Roboti</a></main></body></html>'
    (output/'404.html').write_text(error,encoding='utf-8')
print(f'GitHub Pages: {len(records)} fichas + 8 páginas, sin PHP ni base de datos pública. Salida: {output}')
