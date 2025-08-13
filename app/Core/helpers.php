<?php

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