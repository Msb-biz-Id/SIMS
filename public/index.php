<?php
declare(strict_types=1);

ini_set('display_errors', '1');
error_reporting(E_ALL);

require __DIR__ . '/../app/Core/Autoloader.php';
require __DIR__ . '/../app/Core/helpers.php';

// Load environment
load_env(__DIR__ . '/../.env');

// Composer autoload (PHPMailer, dll)
$vendorAutoload = __DIR__ . '/../vendor/autoload.php';
if (is_file($vendorAutoload)) {
    require $vendorAutoload;
}

// Security headers
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer-when-downgrade');
header('X-XSS-Protection: 0');
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
if ($secure) {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
}
// CSP baseline (relaxed for existing assets); refine incrementally
$csp = "default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://code.jquery.com https://cdn.datatables.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdn.datatables.net; img-src 'self' data:; font-src 'self' data:; connect-src 'self'; frame-ancestors 'self'; base-uri 'self'; form-action 'self'";
header('Content-Security-Policy: ' . $csp);

// Sessions
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// Ensure our own cookies if any are also secure
if (!headers_sent()) {
    foreach (['PHPSESSID'] as $cookie) {
        if (isset($_COOKIE[$cookie])) {
            setcookie($cookie, $_COOKIE[$cookie], [
                'expires' => 0,
                'path' => '/',
                'domain' => '',
                'secure' => $secure,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
        }
    }
}

date_default_timezone_set(app_config()['timezone'] ?? 'UTC');

App\Core\Autoloader::register();

$routes = require __DIR__ . '/../app/Config/routes.php';

$router = new App\Core\Router($routes);
$router->dispatch();