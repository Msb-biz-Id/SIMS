<?php
namespace App\Core;

use App\Core\Model;
use PDO;

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
}