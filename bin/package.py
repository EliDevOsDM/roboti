"""Genera un ZIP sin credenciales, sesiones ni bases de datos de ejecución."""
from pathlib import Path
from zipfile import ZipFile, ZIP_DEFLATED

root=Path(__file__).resolve().parents[1]
target=root/'roboti-php.zip'
with ZipFile(target,'w',ZIP_DEFLATED) as z:
    for directory in ['app','bin','database','public','tests']:
        for p in sorted((root/directory).rglob('*')):
            if p.is_file() and '__pycache__' not in p.parts:
                z.write(p,p.relative_to(root).as_posix())
    for name in ['README.md','LEEME-PHPMYADMIN.md','DESIGN.md','BRIEF-WEB-ROBOTI.md','VERIFICACION.md','.env.example','.gitignore','storage/.gitkeep','storage/.htaccess']:
        z.write(root/name,name)
with ZipFile(target) as z:
    assert 'public/index.php' in z.namelist()
    assert 'database/roboti.mysql.sql' in z.namelist()
    assert '.env.example' in z.namelist()
    assert '.env' not in z.namelist()
    assert not any(n.endswith('.sqlite') for n in z.namelist())
    assert z.testzip() is None
    print(f'{target.name}: {len(z.namelist())} archivos, {target.stat().st_size:,} bytes')
