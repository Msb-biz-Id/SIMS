<?php
namespace App\SistemDataMaster\Models;

use App\Core\Model;
use PDO;

final class Lembaga extends Model
{
    public function all(): array
    {
        return $this->db->query('SELECT * FROM lembaga ORDER BY id DESC')->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM lembaga WHERE id=?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(string $name, ?string $description, bool $isKeuangan, ?int $parentId, ?string $logoPath): int
    {
        $stmt = $this->db->prepare('INSERT INTO lembaga(name, description, is_keuangan, parent_id, logo_path) VALUES(?,?,?,?,?)');
        $stmt->execute([$name, $description, $isKeuangan ? 1 : 0, $parentId, $logoPath]);
        return (int) $this->db->lastInsertId();
    }

    public function updateLembaga(int $id, string $name, ?string $description, bool $isKeuangan, ?int $parentId, ?string $logoPath): void
    {
        if ($logoPath !== null) {
            $stmt = $this->db->prepare('UPDATE lembaga SET name=?, description=?, is_keuangan=?, parent_id=?, logo_path=? WHERE id=?');
            $stmt->execute([$name, $description, $isKeuangan ? 1 : 0, $parentId, $logoPath, $id]);
            return;
        }
        $stmt = $this->db->prepare('UPDATE lembaga SET name=?, description=?, is_keuangan=?, parent_id=? WHERE id=?');
        $stmt->execute([$name, $description, $isKeuangan ? 1 : 0, $parentId, $id]);
    }

    public function deleteLembaga(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM lembaga WHERE id=?');
        $stmt->execute([$id]);
    }
}