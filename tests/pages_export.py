"""Comprueba que el artefacto de Pages solo contiene vistas públicas y enlaces válidos."""
from html.parser import HTMLParser
from pathlib import Path
from urllib.parse import urljoin, urlparse, unquote
import json

root=Path(__file__).resolve().parents[1]/'build/pages'
assert root.is_dir(), 'Compila primero con python bin/build-pages.py'
records=json.loads((root/'assets/courses.json').read_text(encoding='utf-8'))
assert len(records)==77
assert len(list((root/'programas').glob('*.html')))==77
assert len(list((root/'reglamentos').glob('*.pdf')))==12
assert not (root/'admin').exists()
assert not any(p.suffix in ['.php','.sql','.sqlite'] for p in root.rglob('*') if p.is_file())
assert not (root/'.env').exists()

class Links(HTMLParser):
    def __init__(self): super().__init__();self.links=[]
    def handle_starttag(self,tag,attrs):
        self.links.extend(v for k,v in attrs if k in ['href','src'] and v)

checked=0
for page in root.rglob('*.html'):
    html=page.read_text(encoding='utf-8')
    assert 'Fatal error' not in html and '<?php' not in html
    parser=Links();parser.feed(html)
    for link in parser.links:
        u=urlparse(urljoin('https://example.test/roboti/'+page.relative_to(root).as_posix(),link))
        if u.netloc!='example.test':continue
        assert u.path.startswith('/roboti/'),f'Ruta fuera de la base: {link}'
        target=root/unquote(u.path[len('/roboti/'):])
        assert target.is_file(),f'Enlace roto en {page.name}: {link}'
        assert '.php' not in u.path,f'Ruta PHP en Pages: {link}'
        checked+=1
print(f'OK Pages: {len(records)} cursos, 12 reglamentos, {checked} enlaces y recursos verificados; sin archivos privados.')
