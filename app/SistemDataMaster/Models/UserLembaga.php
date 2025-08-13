<?php
namespace App\SistemDataMaster\Models;

use App\Core\Model;

final class UserLembaga extends Model
{
    public function getUserLembagaIds(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT lembaga_id FROM user_lembaga WHERE user_id=?');
        $stmt->execute([$userId]);
        return array_map('intval', array_column($stmt->fetchAll(), 'lembaga_id'));
    }

    public function assign(int $userId, array $lembagaIds): void
    {
        $this->db->beginTransaction();
        $this->db->prepare('DELETE FROM user_lembaga WHERE user_id=?')->execute([$userId]);
        $ins = $this->db->prepare('INSERT INTO user_lembaga(user_id, lembaga_id) VALUES(?,?)');
        foreach ($lembagaIds as $id) {
            $ins->execute([$userId, (int) $id]);
        }
        $this->db->commit();
    }
}