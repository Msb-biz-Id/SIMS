<?php

function load_env(string $path): void
{
    if (!is_file($path) || !is_readable($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || str_starts_with($line, ';')) {
            continue;
        }
        if (strpos($line, '=') === false) {
            continue;
        }
        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if ((str_starts_with($value, '"') && str_ends_with($value, '"')) || (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
            $value = substr($value, 1, -1);
        }
        putenv($name . '=' . $value);
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}

function app_config(): array
{
    static $config;
    if ($config === null) {
        $configPath = __DIR__ . '/../Config/app.php';
        $config = is_file($configPath) ? require $configPath : [];
    }
    return $config;
}

function infer_base_url(): string
{
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443);
    $scheme = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
    $publicDir = rtrim(str_replace('index.php', '', $scriptName), '/');
    if ($host) {
        return $scheme . '://' . $host . ($publicDir === '' ? '/' : $publicDir . '/');
    }
    return ($publicDir === '' ? '/' : $publicDir . '/');
}

function base_url(string $path = ''): string
{
    $config = app_config();
    $base = $config['base_url'] ?? infer_base_url();
    $base = rtrim($base, '/') . '/';
    $path = ltrim($path, '/');
    return $base . $path;
}

function asset_url(string $path): string
{
    return base_url('assets/' . ltrim($path, '/'));
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    $token = csrf_token();
    return '<input type="hidden" name="_token" value="' . e($token) . '">';
}

function verify_csrf(): bool
{
    $token = $_POST['_token'] ?? '';
    return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][$type] = $message;
}

function flash_get(string $type): ?string
{
    if (!empty($_SESSION['flash'][$type])) {
        $msg = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $msg;
    }
    return null;
}

function sanitize_filename(string $name): string
{
    $name = preg_replace('/[^A-Za-z0-9_\.-]/', '_', $name);
    return trim($name, '_');
}

function rate_limit_check(string $key, int $maxAttempts, int $windowSeconds): bool {
    $now = time();
    if (!isset($_SESSION['rate_limit'])) { $_SESSION['rate_limit'] = []; }
    if (!isset($_SESSION['rate_limit'][$key])) { $_SESSION['rate_limit'][$key] = []; }
    // remove old entries
    $_SESSION['rate_limit'][$key] = array_filter($_SESSION['rate_limit'][$key], function($ts) use ($now, $windowSeconds) {
        return ($now - (int)$ts) < $windowSeconds;
    });
    if (count($_SESSION['rate_limit'][$key]) >= $maxAttempts) {
        return false;
    }
    $_SESSION['rate_limit'][$key][] = $now;
    return true;
}

function sql_in_clause(array $values, string $prefix = 'p'): array {
    $placeholders = [];
    $params = [];
    $i = 0;
    foreach ($values as $v) {
        $name = ':' . $prefix . $i++;
        $placeholders[] = $name;
        $params[$name] = $v;
    }
    return [implode(',', $placeholders), $params];
}