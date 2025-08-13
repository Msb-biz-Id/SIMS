<?php
namespace App\Core;

use PDO;

final class Settings
{
    public static function get(string $key, ?string $default = null): ?string
    {
        $stmt = Database::connection()->prepare('SELECT `value` FROM settings WHERE `key`=? LIMIT 1');
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        return $val !== false ? (string) $val : $default;
    }

    public static function set(string $key, ?string $value): void
    {
        $stmt = Database::connection()->prepare('INSERT INTO settings(`key`,`value`) VALUES(?,?) ON DUPLICATE KEY UPDATE `value`=VALUES(`value`)');
        $stmt->execute([$key, $value]);
    }
}