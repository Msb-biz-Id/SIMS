<?php
namespace App\Core;

use App\Core\Model;

final class Access
{
    public static function isSuperAdmin(int $userId): bool
    {
        $stmt = Model::db()->prepare("SELECT 1 FROM roles r JOIN user_roles ur ON ur.role_id=r.id WHERE ur.user_id=? AND r.name='admin' LIMIT 1");
        $stmt->execute([$userId]);
        return (bool) $stmt->fetchColumn();
    }

    public static function userHasLembaga(int $userId, int $lembagaId): bool
    {
        $stmt = Model::db()->prepare('SELECT 1 FROM user_lembaga WHERE user_id=? AND lembaga_id=? LIMIT 1');
        $stmt->execute([$userId, $lembagaId]);
        return (bool) $stmt->fetchColumn();
    }

    public static function getUserLembagaIds(int $userId): array
    {
        if (self::isSuperAdmin($userId)) {
            $rows = Model::db()->query('SELECT id FROM lembaga')->fetchAll();
            return array_map('intval', array_column($rows, 'id'));
        }
        $stmt = Model::db()->prepare('SELECT lembaga_id FROM user_lembaga WHERE user_id=?');
        $stmt->execute([$userId]);
        return array_map('intval', array_column($stmt->fetchAll(), 'lembaga_id'));
    }

    public static function getUserKeuanganLembagaIds(int $userId): array
    {
        if (self::isSuperAdmin($userId)) {
            $rows = Model::db()->query('SELECT id FROM lembaga WHERE is_keuangan=1')->fetchAll();
            return array_map('intval', array_column($rows, 'id'));
        }
        $stmt = Model::db()->prepare('SELECT ul.lembaga_id FROM user_lembaga ul JOIN lembaga l ON l.id=ul.lembaga_id WHERE ul.user_id=? AND l.is_keuangan=1');
        $stmt->execute([$userId]);
        return array_map('intval', array_column($stmt->fetchAll(), 'lembaga_id'));
    }
}