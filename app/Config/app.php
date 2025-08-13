<?php
return [
    'app_name' => 'Platform Multi-Sistem Terintegrasi Lembaga',
    'env' => getenv('APP_ENV') ?: 'development',
    'base_url' => getenv('APP_BASE_URL') ?: null,
    'timezone' => getenv('TZ') ?: 'Asia/Jakarta',
    'turnstile_site_key' => getenv('TURNSTILE_SITE_KEY') ?: '',
    'turnstile_secret_key' => getenv('TURNSTILE_SECRET_KEY') ?: '',
];