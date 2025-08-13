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

// Sessions
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set(app_config()['timezone'] ?? 'UTC');

App\Core\Autoloader::register();

$routes = require __DIR__ . '/../app/Config/routes.php';

$router = new App\Core\Router($routes);
$router->dispatch();