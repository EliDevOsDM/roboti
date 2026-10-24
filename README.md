# Roboti Academy

Sitio responsive en PHP 8.2 o superior, HTML, CSS y JavaScript, sin framework ni Composer. Catálogo consultado desde MySQL mediante PDO, administrable desde phpMyAdmin. MySQL es el motor predeterminado; SQLite solo se utiliza si se elige expresamente para pruebas. Incluye 77 propuestas importadas del documento recibido, fichas individuales, filtros, búsqueda sin distinción de acentos, paginación y panel privado.

Para instalar desde phpMyAdmin, sigue `LEEME-PHPMYADMIN.md`. El archivo para importar es `database/roboti.mysql.sql`.

## GitHub Pages

La versión pública de presentación se publica automáticamente con `.github/workflows/pages.yml` al actualizar `main`. El workflow prueba PHP y exporta únicamente HTML, CSS, JavaScript, fuentes, imágenes y reglamentos. El catálogo de Pages permite buscar y filtrar en el navegador; las 77 fichas tienen sus propias URL. La versión PHP conserva la administración y consulta dinámica de MySQL para un hosting que ejecute PHP.

GitHub Pages no ejecuta PHP ni conecta con MySQL. Su catálogo es una instantánea de `database/courses.json`, regenerada en cada despliegue. Los cambios del panel en un hosting PHP no se sincronizan automáticamente con ese archivo. No se publican el panel, credenciales, SQL ni bases de ejecución dentro del artefacto de Pages; el código fuente y el SQL sí están en el repositorio como parte del proyecto solicitado.

Compilación local: `python bin/build-pages.py --base-path /roboti`. Verificación: `python tests/pages_export.py`. La salida se guarda en `build/pages` y no se versiona. Para repetir la compilación, usa una carpeta limpia. El despliegue debe tener habilitado **Settings → Pages → GitHub Actions** en el repositorio.

## Abrir en este equipo Windows

Desde la carpeta del proyecto, en PowerShell:

```powershell
./bin/dev.ps1
```

Configura primero MySQL en `.env` como se explica abajo. Visita http://127.0.0.1:8080. El script activa los drivers PDO incluidos en la instalación de PHP, crea las tablas de MySQL si hace falta y conserva los cursos existentes. Detén el servidor con Ctrl+C. Puedes elegir otro puerto: `./bin/dev.ps1 -Port 8090`.

Para probar sin MySQL, elige SQLite explícitamente: `./bin/dev.ps1 -Database sqlite`. La base de pruebas local se guarda en `storage/roboti.sqlite`, fuera del directorio público. En ese modo, crea el administrador con `./bin/console.ps1 admin tu-correo@dominio.com -Database sqlite`. No se cambia automáticamente a SQLite si falla la conexión MySQL.

## Crear tu administrador

En otra terminal:

```powershell
./bin/console.ps1 admin tu-correo@dominio.com
```

Introduce una contraseña propia de al menos 12 caracteres. La entrada estándar puede ser visible en algunas terminales; no la grabes ni la compartas. No hay usuario ni contraseña predeterminados. Acceso: http://127.0.0.1:8080/admin/login.php.

En Linux o un servidor con extensiones configuradas:

```sh
php bin/create-admin.php tu-correo@dominio.com
```

El panel permite crear, editar, destacar, publicar, retirar y archivar programas. Edita nombre, dirección, línea, nivel, edad, tecnología, duración, horario, inicio, sede, inversión, temario, requisitos e imagen. Los borradores y archivados no aparecen en fichas ni resultados públicos. Archivar conserva el registro. Para agregar fotografías, coloca un archivo WebP, PNG o JPEG en `public/assets/images/`; aparecerá en el selector del panel. Las imágenes se gestionan por archivos, sin subida pública.

## MySQL y alojamiento PHP

1. Crea una base de datos y un usuario MySQL con acceso a esa base.
2. Copia `.env.example` a `.env` y completa `DB_CONNECTION=mysql`, host, puerto, base, usuario y contraseña. Configura `APP_URL` con el origen y, si aplica, el subdirectorio de la web, sin barra final.
3. Activa `pdo_mysql` en PHP. `iconv`, sesiones y PDO son necesarios. Para SQLite, activa `pdo_sqlite`.
4. Ejecuta `php bin/init.php`. Crea las tablas e importa el inventario únicamente si la tabla de cursos está vacía. Las ediciones existentes no se sustituyen.
5. Crea el administrador con el comando anterior.
6. Apunta el document root del dominio a `public/`. `app/`, `database/`, `storage/`, `.env`, `bin/` y `tests/` deben quedar fuera del acceso web. Usa HTTPS en producción.

Como alternativa a la inicialización de la base por consola, importa `database/roboti.mysql.sql` en una base vacía mediante phpMyAdmin. El archivo contiene el esquema y las 77 propuestas; no contiene administradores. Ejecuta después el comando de creación de administrador. El dump se regenera con `php bin/export-mysql.php`.

Si el hosting fija `public_html` como raíz, coloca el contenido de `public/` en esa carpeta y las carpetas privadas junto a ella, conservando la relación entre rutas: `dirname(__DIR__)` de cada página debe apuntar a la carpeta que contiene `app/`. Nunca subas el proyecto entero a una carpeta navegable. No cambies el stack a un exportado HTML: PHP debe ejecutarse en el servidor para consultar y administrar cursos.

La vista pública está desplegada en https://elidevosdm.github.io/roboti/. La aplicación PHP con administración y MySQL necesita un alojamiento compatible. No se ha desplegado ese backend ni probado una conexión MySQL real en este equipo; se ha verificado el flujo completo con SQLite.

## Contacto

En `.env`, configura los datos oficiales cuando el propietario los confirme:

```dotenv
CONTACT_WHATSAPP=593XXXXXXXXX
CONTACT_EMAIL=correo@dominio.com
```

WhatsApp tiene prioridad cuando se especifican ambos. La consulta conserva el nombre del programa. Si no hay un canal configurado, la página informa que los canales se anunciarán y no simula enviar mensajes. No hay formulario público que guarde datos de menores, inscripción al torneo ni pagos.

## Contenido que falta confirmar

El manual de marca y el logo principal definitivo no fueron recibidos. La cabecera es provisional. Precios, materiales, sedes de cursos, horas exactas, inicios y disponibilidad no se inventaron: los campos están preparados en el panel. No se infirieron las edades de LEGO/Arduino Essentials a partir de una tabla ambigua.

El propietario debe confirmar si las 56 propuestas EDGE son cursos independientes, módulos o proyectos rotativos. Se presentan como programas y proyectos en catálogo, sin prometer grupos abiertos. Revisar los títulos completos y sus acreditaciones antes de ofrecer certificados.

Open Games incluye la fecha de inicio publicada (28 de noviembre de 2026, 08:00), 11 categorías y los 12 reglamentos originales. No anuncia un cierre definitivo porque el afiche y el cronograma discrepan. Se señalan las dudas sobre Minisumo y Drones. El enlace de inscripción oficial no fue suministrado. El detalle de discrepancias está en `BRIEF-WEB-ROBOTI.md`.

Para una nueva edición, actualiza `app/event.php`, `public/open-games.php`, los enlaces de `app/view.php` y el resumen en `public/index.php`. El catálogo de cursos es dinámico; la información institucional y del evento se mantiene en archivos PHP.

## Estructura

```text
app/          Conexión PDO, consultas, vistas y validación del panel
bin/          Inicialización, administrador y servidor local
database/     Inventario inicial JSON y dump MySQL
public/       Único directorio que debe ser público
  admin/      Panel con autenticación
  assets/     CSS, JavaScript e imágenes locales
  reglamentos/ PDF originales del torneo
storage/      Base SQLite local, fuera de la web
tests/        Pruebas HTTP con base de datos temporal
```

## Verificación

```sh
python tests/integration.py
```

Requiere Python 3 y PHP accesible en PATH. Crea una base temporal, importa datos y prueba las páginas, los filtros, las 77 fichas, los 12 PDF, creación/edición/publicación/archivo, persistencia, validaciones, autenticación, limitación de intentos, CSRF y escape de HTML. No modifica el catálogo ni los administradores locales. No ejecuta llamadas externas de contacto.

Revisado visualmente en navegador de escritorio y móvil. Los controles son semánticos, el menú soporta Escape y el catálogo funciona con formularios GET sin JavaScript. El panel usa sesiones, regeneración de ID, contraseñas hasheadas, consultas preparadas y operaciones de escritura POST con CSRF.

## Recursos gráficos

La dirección visual está en `DESIGN.md`. Las siete fotografías aportadas por el propietario aparecen en portada, catálogo, fichas, Nosotros y Método EDGE, con versiones WebP de 600 y 1200 píxeles y carga adaptable. El catálogo inicial MySQL incluye estas fotos; las bases existentes que usan las tres ilustraciones iniciales reciben la nueva presentación sin alterar datos ni imágenes personalizadas. Logos del club, torneo y afiche también fueron aportados por el usuario. Las ilustraciones conceptuales anteriores permanecen como recursos opcionales en el panel.


## Rediseño visual — 24 de octubre de 2026

La interfaz adopta la nueva referencia Rising Sun: portada con fotografía de ancho completo, navegación transparente que cambia a blanco al desplazar, secciones blancas y azul suave, tarjetas redondeadas y preguntas frecuentes. El azul principal sigue siendo #092950, extraído del logo. Se retira el lima y se mantiene un pie compacto. DM Sans se sirve localmente con su licencia OFL.

El catálogo utiliza filtros superiores y conserva sus funciones. Las fichas, la academia, EDGE, Roboticlub, Open Games y contacto comparten la nueva dirección visual. Consulta `DESIGN.md` y `ANALISIS-FOTOGRAFIAS.md` para el criterio de composición y la clasificación de imágenes. PHP/MySQL y GitHub Pages mantienen sus modos de funcionamiento anteriores.
