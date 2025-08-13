<?php
namespace App\Core;

final class Router
{
    public function __construct(private array $routes)
    {
    }

    public function dispatch(): void
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $path = rtrim($path, '/') ?: '/';

        if (!isset($this->routes[$path])) {
            http_response_code(404);
            echo '404 Not Found';
            return;
        }

        $handler = $this->routes[$path];

        $module = $handler['module'] ?? null;
        $controller = $handler['controller'] ?? null;
        $action = $handler['action'] ?? null;
        $middlewares = $handler['middleware'] ?? [];

        if (!$module || !$controller || !$action) {
            http_response_code(500);
            echo 'Invalid route handler configuration';
            return;
        }

        $class = 'App\\' . $module . '\\Controllers\\' . $controller . 'Controller';
        if (!class_exists($class)) {
            http_response_code(500);
            echo 'Controller not found: ' . $class;
            return;
        }

        if (!empty($middlewares)) {
            Middleware::handle(is_array($middlewares) ? $middlewares : [$middlewares]);
        }

        $instance = new $class();
        if (!method_exists($instance, $action)) {
            http_response_code(500);
            echo 'Action not found: ' . $class . '::' . $action;
            return;
        }

        $instance->$action();
    }
}