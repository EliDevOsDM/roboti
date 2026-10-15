"""Pruebas HTTP reales contra una base SQLite temporal. No modifica el catálogo local."""
from pathlib import Path
import html
import http.cookiejar
import json
import os
import re
import secrets
import shutil
import socket
import subprocess
import tempfile
import time
import urllib.error
import urllib.parse
import urllib.request

ROOT = Path(__file__).resolve().parents[1]
PHP = shutil.which('php')
assert PHP, 'PHP no está disponible'
args = [PHP]
ext = Path(PHP).parent / 'ext'
if (ext / 'php_pdo_sqlite.dll').exists():
    args += ['-d', f'extension_dir={ext}', '-d', 'extension=pdo_sqlite']

checks = 0
def expect(condition, label):
    global checks
    assert condition, label
    checks += 1

with tempfile.TemporaryDirectory(prefix='roboti-test-', dir=ROOT/'storage') as folder:
    assert Path(folder).resolve().parent == (ROOT/'storage').resolve()
    env = dict(os.environ, DB_CONNECTION='sqlite', SQLITE_PATH=str(Path(folder)/'test.sqlite'), APP_URL='', CONTACT_EMAIL='', CONTACT_WHATSAPP='')
    first = subprocess.run(args+['bin/init.php'], cwd=ROOT, env=env, capture_output=True, text=True, check=True)
    expect('77' in first.stdout, 'Importación inicial de 77 propuestas')
    second = subprocess.run(args+['bin/init.php'], cwd=ROOT, env=env, capture_output=True, text=True, check=True)
    expect('77 propuestas importadas' not in second.stdout, 'Inicialización idempotente')
    password = secrets.token_urlsafe(24)
    subprocess.run(args+['bin/create-admin.php', 'pruebas@example.test'], input=password+'\n', cwd=ROOT, env=env, capture_output=True, text=True, check=True)
    with socket.socket() as s:
        s.bind(('127.0.0.1',0))
        port = s.getsockname()[1]
    server = subprocess.Popen(args+['-S',f'127.0.0.1:{port}','-t','public'], cwd=ROOT, env=env, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL, creationflags=getattr(subprocess,'CREATE_NO_WINDOW',0))
    base=f'http://127.0.0.1:{port}'
    jar=http.cookiejar.CookieJar()
    client=urllib.request.build_opener(urllib.request.HTTPCookieProcessor(jar))
    def request(path, data=None):
        payload=urllib.parse.urlencode(data).encode() if data is not None else None
        try:
            with client.open(base+path, data=payload, timeout=10) as r:
                return r.status,r.read().decode('utf-8',errors='replace'),r.geturl()
        except urllib.error.HTTPError as e:
            return e.code,e.read().decode('utf-8',errors='replace'),e.geturl()
    def token(body):
        return re.search(r'name="csrf" value="([^"]+)"',body).group(1)
    try:
        for _ in range(40):
            try:
                if request('/')[0]==200: break
            except urllib.error.URLError: time.sleep(.1)
        for path in ['/', '/programas.php','/metodologia.php','/club.php','/open-games.php','/nosotros.php','/contacto.php','/privacidad.php']:
            status,body,_=request(path)
            expect(status==200 and '<html lang="es">' in body, 'Página pública '+path)
            expect('Warning:' not in body and 'Fatal error' not in body, 'Página sin errores PHP '+path)
        status,body,_=request('/programas.php?line=essentials')
        expect('<strong>5</strong>' in body,'Filtro Essentials')
        expect('<strong>56</strong>' in request('/programas.php?line=edge')[1], 'Filtro EDGE')
        expect('<strong>16</strong>' in request('/programas.php?line=adultos')[1], 'Filtro adultos')
        expect('Maker Domótica Bot' in request('/programas.php?q=domotica')[1], 'Búsqueda en base de datos')
        expect('<strong>16</strong>' in request('/programas.php?line=edge&age=4')[1], 'Filtro de edad EDGE')
        expect('Probemos otro camino' in request('/programas.php?q=zzzzzzzz-no-existe')[1], 'Estado vacío')
        for c in json.loads((ROOT/'database/courses.json').read_text(encoding='utf-8')):
            expect(request('/programa.php?slug='+c['slug'])[0]==200,'Ficha '+c['slug'])
        expect(request('/programa.php?slug=no-existe')[0]==404,'Ficha inexistente devuelve 404')
        expect('<strong>0</strong>' in request('/programas.php?q='+urllib.parse.quote("' OR 1=1 --"))[1], 'Consulta parametrizada')
        body=request('/programas.php?q='+urllib.parse.quote('<script>alert(1)</script>'))[1]
        expect('<script>alert(1)</script>' not in body and '&lt;script&gt;' in body,'Escape de búsqueda XSS')
        for pdf in (ROOT/'public/reglamentos').glob('*.pdf'):
            with client.open(base+'/reglamentos/'+urllib.parse.quote(pdf.name)) as response:
                expect(response.status==200 and response.read(4)==b'%PDF','Descarga '+pdf.name)
        status,body,final=request('/admin/index.php')
        expect(final.endswith('/admin/login.php'),'Protección del panel privado')
        expect(request('/admin/login.php',{'email':'pruebas@example.test','password':password})[0]==419,'CSRF obligatorio en login')
        status,body,_=request('/admin/login.php')
        status,body,final=request('/admin/login.php',{'csrf':token(body),'email':'pruebas@example.test','password':password})
        expect(final.endswith('/admin/index.php') and 'Tus programas' in body,'Autenticación de administrador')
        csrf=token(body)
        data=dict(csrf=csrf,title='Prueba dinámica <script>alert(1)</script>',slug='prueba-dinamica',full_title='Prueba',description='Contenido persistido desde el panel',line='edge',track='maker',technology='Arduino',age_min=12,age_max=14,duration_months=3,weekly_hours=3,schedule='Miércoles 16:00',price='65',price_period='mensual',materials='Kit incluido',requirements='Consultar',syllabus='Sensores\nProgramación',start_date='2027-01-20',location='Sede de prueba',image='electronics.webp',status='draft',sort_order=0)
        status,body,final=request('/admin/edit.php',data)
        expect(final.endswith('?updated=1'),'Crear propuesta desde formulario')
        expect(request('/programa.php?slug=prueba-dinamica')[0]==404,'Borrador no visible públicamente')
        import sqlite3
        con=sqlite3.connect(Path(folder)/'test.sqlite')
        course_id=con.execute('SELECT id FROM courses WHERE slug=?',('prueba-dinamica',)).fetchone()[0]
        expect(request('/admin/index.php',{'id':course_id,'status':'published'})[0]==419,'CSRF obligatorio en publicaciones')
        status,body,_=request('/admin/index.php',{'csrf':csrf,'id':course_id,'status':'published'})
        public=request('/programa.php?slug=prueba-dinamica')
        expect(public[0]==200 and '$65.00 mensual' in public[1],'Publicación dinámica con precio')
        expect('&lt;script&gt;alert(1)&lt;/script&gt;' in public[1] and '<script>alert(1)</script>' not in public[1],'Escape de contenido editado')
        data['title']='Curso actualizado'; data['price']='75'; data['status']='published'
        request('/admin/edit.php?id='+str(course_id),data)
        expect('$75.00 mensual' in request('/programa.php?slug=prueba-dinamica')[1],'Edición persistente visible en web')
        data['slug']='microsoldadura'
        expect('Ya existe un programa' in request('/admin/edit.php',data)[1],'Slug único controlado')
        data['slug']='prueba-invalida';data['age_min']=15;data['age_max']=8;data['start_date']='2027-02-30';data['price_period']=''
        invalid=request('/admin/edit.php',data)[1]
        expect('Revisa el rango' in invalid and 'fecha de inicio no es válida' in invalid and 'Aclara si el precio' in invalid,'Validación de edades, fecha y precio')
        request('/admin/index.php',{'csrf':csrf,'id':course_id,'status':'archived'})
        expect(request('/programa.php?slug=prueba-dinamica')[0]==404,'Archivado retira de catálogo sin borrar')
        expect(con.execute('SELECT COUNT(*) FROM courses WHERE id=?',(course_id,)).fetchone()[0]==1,'Archivo conserva el registro')
        con.close()
        status,_,_=request('/admin/logout.php',{'csrf':csrf})
        expect(request('/admin/index.php')[2].endswith('/admin/login.php'),'Cierre de sesión')
        for n in range(11):
            _,body,_=request('/admin/login.php')
            status,_,_=request('/admin/login.php',{'csrf':token(body),'email':'inexistente@example.test','password':'incorrecta'})
        expect(status==429,'Limitación de intentos de acceso')
        print(f'OK: {checks} comprobaciones. Base de pruebas aislada; catálogo real intacto.')
    finally:
        server.terminate()
        server.wait(timeout=10)
