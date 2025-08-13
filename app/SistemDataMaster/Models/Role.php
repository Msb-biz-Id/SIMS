<?php
namespace App\SistemDataMaster\Models;

use App\Core\Model;
use PDO;

final class Role extends Model
{
    public function all(): array
    {
        return $this->db->query('SELECT * FROM roles ORDER BY name ASC')->fetchAll();
    }

    public function getUserRoleNames(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT r.name FROM roles r JOIN user_roles ur ON ur.role_id=r.id WHERE ur.user_id=?');
        $stmt->execute([$userId]);
        return array_column($stmt->fetchAll(), 'name');
    }

    public function assignRoles(int $userId, array $roleIds): void
    {
        $this->db->beginTransaction();
        $this->db->prepare('DELETE FROM user_roles WHERE user_id=?')->execute([$userId]);
        $ins = $this->db->prepare('INSERT INTO user_roles(user_id, role_id) VALUES(?,?)');
        foreach ($roleIds as $rid) {
            $ins->execute([$userId, (int)$rid]);
        }
        $this->db->commit();
    }

    public function userHasRole(int $userId, string $roleName): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM roles r JOIN user_roles ur ON ur.role_id=r.id WHERE ur.user_id=? AND r.name=? LIMIT 1');
        $stmt->execute([$userId, $roleName]);
        return (bool) $stmt->fetchColumn();
    }
}