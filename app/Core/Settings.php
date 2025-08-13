<?php
namespace App\Core;

use PDO;

final class Settings
{
    private static array $cache = [];

    public static function get(string $key, ?string $default = null): ?string
    {
        if (array_key_exists($key, self::$cache)) {
            return self::$cache[$key];
        }
        $stmt = Database::connection()->prepare('SELECT `value` FROM settings WHERE `key`=? LIMIT 1');
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        $val = $val !== false ? (string) $val : $default;
        self::$cache[$key] = $val;
        return $val;
    }

    public static function set(string $key, ?string $value): void
    {
        $stmt = Database::connection()->prepare('INSERT INTO settings(`key`,`value`) VALUES(?,?) ON DUPLICATE KEY UPDATE `value`=VALUES(`value`)');
        $stmt->execute([$key, $value]);
        self::$cache[$key] = $value;
    }
}