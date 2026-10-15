# Instalar Roboti con MySQL desde phpMyAdmin

La web está desarrollada en PHP y almacena los cursos en MySQL. phpMyAdmin permite administrar esa base; el archivo SQL contiene sus tablas y datos iniciales.

## Importar las tablas y los cursos

1. Abre phpMyAdmin.
2. Crea una base vacía llamada `roboti`, o selecciona la base que te asigne tu hosting. Usa `utf8mb4_unicode_ci` si te permite elegir el cotejamiento.
3. Dentro de esa base, abre **Importar**.
4. Selecciona `database/roboti.mysql.sql`, conserva el formato SQL y la codificación UTF-8, y ejecuta la importación.
5. Comprueba que aparecen `courses`, `admins` y `login_attempts`. `courses` debe tener 77 filas; `admins` comienza vacía.

Importa el inventario inicial una sola vez en una base vacía. Para actualizar los cursos después, usa el panel de la web. El archivo no elimina tablas ni sobrescribe cursos existentes; repetir sus INSERT sobre los mismos datos produce conflictos de slug único.

## Conectar la web

Copia `.env.example` a `.env`, en la raíz privada del proyecto, y completa los datos que muestra tu hosting:

```dotenv
APP_ENV=production
APP_URL=
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nombre_real_de_tu_base
DB_USERNAME=usuario_mysql
DB_PASSWORD=tu_clave_mysql
```

Los datos son los de MySQL, no necesariamente el correo y la contraseña con los que accedes a phpMyAdmin. `APP_URL` puede quedar vacío si `public/` es la raíz del dominio; si está en un subdirectorio, indica su URL completa.

El servidor necesita PHP 8.2 o superior con `pdo_mysql`. Configura `public/` como raíz pública; `.env`, `app/`, `database/`, `storage/` y `bin/` deben quedar fuera del acceso web. El README incluye la adaptación para `public_html`.

## Crear el administrador

Desde la terminal del hosting:

```sh
php bin/create-admin.php tu-correo@dominio.com
```

En este equipo Windows:

```powershell
./bin/console.ps1 admin tu-correo@dominio.com
```

Introduce una contraseña propia de al menos 12 caracteres. No uses la contraseña de MySQL como contraseña del panel. Accede a `/admin/login.php` para crear, editar, publicar o archivar los cursos.

Si el hosting no ofrece terminal, la importación SQL funciona igual para el catálogo público. La creación del primer administrador requerirá generar un hash con `password_hash` de PHP y añadir ese correo y hash a la tabla `admins`; nunca guardes una contraseña en texto plano. El comando incluido automatiza este paso cuando se dispone de PHP por consola.

No se ha conectado a tu instancia de MySQL porque no se han proporcionado sus datos. El paquete incluye la configuración, el esquema y los datos para esa conexión.
