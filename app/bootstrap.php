<?php
declare(strict_types=1);

define('ROOT', dirname(__DIR__));
if (is_file(ROOT . '/.env')) {
    foreach (file(ROOT . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        if (getenv($key) === false) putenv($key . '=' . trim(trim($value), "\"'"));
    }
}
date_default_timezone_set('America/Guayaquil');
ini_set('display_errors', getenv('APP_ENV') === 'development' ? '1' : '0');
ini_set('log_errors', '1');
function env(string $key, string $default = ''): string { $v = getenv($key); return $v === false ? $default : $v; }
function e(mixed $v): string { return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function url(string $path = ''): string {
    if (env('PAGES_BUILD') === '1') {
        [$page, $query] = array_pad(explode('?', $path, 2), 2, '');
        if ($page === 'programa.php') {
            parse_str($query, $params);
            $path = 'programas/' . rawurlencode((string)($params['slug'] ?? 'no-encontrado')) . '.html';
        } elseif (str_ends_with($page, '.php')) {
            $path = substr($page, 0, -4) . '.html' . ($query !== '' ? '?' . $query : '');
        }
    }
    return rtrim(env('APP_URL'), '/') . '/' . ltrim($path, '/');
}
function asset(string $path): string { return url('assets/' . $path); }
function redirect(string $path): never { header('Location: ' . url($path), true, 303); exit; }
function db(): PDO {
    static $pdo;
    if ($pdo) return $pdo;
    $driver = env('DB_CONNECTION', 'mysql');
    if ($driver === 'mysql') {
        $dsn = 'mysql:host=' . env('DB_HOST', '127.0.0.1') . ';port=' . env('DB_PORT', '3306') . ';dbname=' . env('DB_DATABASE', 'roboti') . ';charset=utf8mb4';
    } elseif ($driver === 'sqlite') {
        $file = env('SQLITE_PATH', ROOT . '/storage/roboti.sqlite');
        if (!preg_match('~^(?:[A-Za-z]:[\\\\/]|/)~', $file)) $file = ROOT . '/' . $file;
        if (!is_file($file) && !defined('INSTALLING')) throw new RuntimeException('La base de datos no está inicializada. Ejecuta php bin/init.php.');
        $dsn = 'sqlite:' . $file;
    } else throw new RuntimeException('Motor de base de datos no permitido.');
    $pdo = new PDO($dsn, env('DB_USERNAME'), env('DB_PASSWORD'), [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    if ($driver === 'mysql') $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    else { $pdo->exec('PRAGMA foreign_keys = ON'); $pdo->exec('PRAGMA busy_timeout = 5000'); }
    return $pdo;
}
function session_start_safe(): void {
    if (session_status() === PHP_SESSION_ACTIVE) return;
    session_name('roboti_admin');
    session_set_cookie_params(['httponly' => true, 'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off', 'samesite' => 'Strict', 'path' => '/']);
    session_start();
    header('Cache-Control: no-store');
}
function csrf(): string { session_start_safe(); return $_SESSION['csrf'] ??= bin2hex(random_bytes(32)); }
function csrf_field(): string { return '<input type="hidden" name="csrf" value="' . e(csrf()) . '">'; }
function check_csrf(): void {
    session_start_safe();
    if (!hash_equals($_SESSION['csrf'] ?? '', (string)($_POST['csrf'] ?? '')) || empty($_SESSION['csrf'])) { http_response_code(419); exit('La sesión expiró. Recarga la página y vuelve a intentarlo.'); }
}
function require_admin(): void {
    session_start_safe();
    if (empty($_SESSION['admin_id'])) redirect('admin/login.php');
    if (time() - ($_SESSION['last_active'] ?? 0) > 3600) { $_SESSION = []; redirect('admin/login.php'); }
    $_SESSION['last_active'] = time();
}
function lines(): array { return ['edge' => 'EDGE', 'essentials' => 'Essentials', 'adultos' => 'Adultos']; }
function tracks(): array { return ['explorer' => 'Explorer', 'builders' => 'Builders', 'maker' => 'Maker', 'engineering' => 'Engineering']; }
function age_label(array $c): string {
    if ($c['age_min'] === null) return $c['line'] === 'adultos' ? 'Jóvenes y adultos' : 'Consultar edad';
    return $c['age_max'] !== null ? $c['age_min'] . '–' . $c['age_max'] . ' años' : 'Desde ' . $c['age_min'] . ' años';
}
function default_course_image(array $c): string {
    $topic = strtolower((string)($c['slug'] ?? '')) . ' ' . search_text(($c['title'] ?? '') . ' ' . ($c['technology'] ?? ''));
    if (($c['line'] ?? '') === 'adultos') {
        if (preg_match('/microsoldadura|reparacion|electronica/', $topic)) return 'foto-electronica-en-equipo.webp';
        if (preg_match('/arduino|circuit|domotica|pcb/', $topic)) return 'foto-circuitos.webp';
        return 'foto-formacion-robotica.webp';
    }
    if (($c['track'] ?? '') === 'explorer' || (isset($c['age_max']) && (int)$c['age_max'] <= 7)) {
        return preg_match('/safari|espacio|planeta/', $topic) ? 'foto-explorer-espacio.webp' : 'foto-explorer-beebot.webp';
    }
    if (($c['track'] ?? '') === 'builders' || ($c['technology'] ?? '') === 'LEGO') return 'foto-taller-robotica.webp';
    if (in_array($c['track'] ?? '', ['maker', 'engineering'], true) || preg_match('/arduino|electronica|domotica|circuit|pcb/', $topic)) return 'foto-circuitos.webp';
    return 'foto-robotica-acompanada.webp';
}
function image_name(array $c): string {
    $name = (string)($c['image'] ?? '');
    // Actualiza los recursos iniciales en bases existentes y respeta imágenes personalizadas.
    if (in_array($name, ['', 'robot-hero.webp', 'robot-builder.webp', 'electronics.webp'], true)) $name = default_course_image($c);
    if (!preg_match('/^[a-zA-Z0-9_-]+\.(webp|png|jpe?g)$/', $name) || !is_file(ROOT . '/public/assets/images/' . $name)) $name = default_course_image($c);
    return $name;
}
function image_url(array $c): string { return asset('images/' . image_name($c)); }
function image_srcset(string $name): string {
    if (!preg_match('/^foto-[a-z0-9-]+\.webp$/', $name)) return '';
    $small = substr($name, 0, -5) . '-600.webp';
    if (!is_file(ROOT . '/public/assets/images/' . $small)) return '';
    return asset('images/' . $small) . ' 600w, ' . asset('images/' . $name) . ' 1200w';
}
function image_alt(array $c): string {
    return match (image_name($c)) {
        'foto-robotica-acompanada.webp' => 'Estudiante e instructor trabajando con un robot y una computadora en Roboti',
        'foto-taller-robotica.webp' => 'Estudiantes de Roboti construyendo robots con piezas modulares',
        'foto-electronica-en-equipo.webp' => 'Participantes de Roboti trabajando juntos en una placa electrónica',
        'foto-explorer-espacio.webp' => 'Grupo infantil explorando un tablero espacial con un robot Bee-Bot',
        'foto-circuitos.webp' => 'Práctica de electrónica con cables y pulsadores sobre una protoboard',
        'foto-formacion-robotica.webp' => 'Participantes en una sesión de formación con un robot humanoide',
        'foto-explorer-beebot.webp' => 'Actividad infantil con robots Bee-Bot, letras y bloques de colores',
        default => 'Imagen del programa ' . ($c['title'] ?? 'de Roboti Academy'),
    };
}
function course_link(array $c): string { return url('programa.php?slug=' . rawurlencode($c['slug'])); }
function courses(array $filters = [], int $limit = 0, int $offset = 0): array {
    [$where, $params] = course_where($filters);
    $sql = 'SELECT * FROM courses WHERE ' . $where . ' ORDER BY featured DESC, sort_order ASC, id ASC';
    if ($limit > 0) $sql .= ' LIMIT ' . $limit . ' OFFSET ' . max(0, $offset);
    $q = db()->prepare($sql); $q->execute($params); return $q->fetchAll();
}
function course_where(array $f): array {
    $where = ["status = 'published'"]; $params = [];
    foreach (['line', 'track', 'technology'] as $key) if (!empty($f[$key])) { $where[] = "$key = ?"; $params[] = $f[$key]; }
    if (($f['age'] ?? '') !== '' && is_numeric($f['age'])) { $where[] = 'age_min IS NOT NULL AND age_min <= ? AND (age_max IS NULL OR age_max >= ?)'; $params[] = (int)$f['age']; $params[] = (int)$f['age']; }
    if (!empty($f['q'])) { $where[] = "search_text LIKE ? ESCAPE '!'"; $term = str_replace(['!','%','_'],['!!','!%','!_'],search_text(mb_fallback_substr($f['q'],150))); $params[] = '%'.$term.'%'; }
    return [implode(' AND ', $where), $params];
}
function mb_fallback_substr(string $s, int $length): string { return function_exists('mb_substr') ? mb_substr($s, 0, $length) : substr($s, 0, $length); }
function search_text(string $s):string { return strtolower(iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$s) ?: $s); }
function course_count(array $filters): int { [$w,$p] = course_where($filters); $q=db()->prepare('SELECT COUNT(*) FROM courses WHERE '.$w);$q->execute($p);return (int)$q->fetchColumn(); }
function contact_target(string $subject = ''): ?string {
    $wa = preg_replace('/\D/', '', env('CONTACT_WHATSAPP'));
    if (strlen($wa) >= 10 && strlen($wa) <= 15) return 'https://wa.me/' . $wa . '?text=' . rawurlencode('Hola, quisiera información ' . ($subject ? 'sobre ' . $subject : 'de Roboti Academy') . '.');
    $email = env('CONTACT_EMAIL');
    return filter_var($email,FILTER_VALIDATE_EMAIL) ? 'mailto:'.$email.'?subject='.rawurlencode('Consulta: '.$subject) : null;
}
if (PHP_SAPI !== 'cli') {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; style-src 'self'; script-src 'self'; font-src 'self'; form-action 'self'; base-uri 'self'; frame-ancestors 'self'");
    set_exception_handler(function (Throwable $ex) {
        error_log((string)$ex); http_response_code(503);
        echo '<!doctype html><html lang="es"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Roboti Academy</title><body><h1>Volvemos en un momento</h1><p>No pudimos cargar la información. Intenta de nuevo más tarde.</p></body></html>';
    });
}
