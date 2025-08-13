<?php
namespace App\ProgramKerja\Models;

use App\Core\Model;
use App\Core\Access;
use PDO;

final class Proker extends Model
{
    public function list(array $filters, int $limit = 100, int $offset = 0): array
    {
        $userId = (int) ($_SESSION['user_id'] ?? 0);
        $allowed = Access::getUserLembagaIds($userId);
        if (empty($allowed)) { return []; }
        $where = ['lembaga_id IN (' . implode(',', array_map('intval', $allowed)) . ')'];
        $params = [];
        if (!empty($filters['lembaga_id'])) { $where[] = 'lembaga_id = :lembaga_id'; $params[':lembaga_id'] = (int)$filters['lembaga_id']; }
        if (!empty($filters['periode_year'])) { $where[] = 'periode_year = :periode'; $params[':periode'] = (int)$filters['periode_year']; }
        $sql = 'SELECT * FROM proker WHERE ' . implode(' AND ', $where) . ' ORDER BY id DESC LIMIT :limit OFFSET :offset';
        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) { $stmt->bindValue($k, $v); }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $userId = (int) $_SESSION['user_id'];
        $allowed = Access::getUserLembagaIds($userId);
        if (!in_array((int)$data['lembaga_id'], $allowed, true)) {
            throw new \RuntimeException('Akses lembaga ditolak');
        }
        $stmt = $this->db->prepare('INSERT INTO proker(lembaga_id, nama, deskripsi, penanggung_jawab_user_id, periode_year, created_by) VALUES(?,?,?,?,?,?)');
        $stmt->execute([(int)$data['lembaga_id'], $data['nama'], $data['deskripsi'] ?? null, (int)($data['pj_user_id'] ?? 0) ?: null, (int)$data['periode_year'], $userId]);
        return (int) $this->db->lastInsertId();
    }

    public function updateProker(int $id, array $data): void
    {
        $row = $this->find($id);
        $userId = (int) $_SESSION['user_id'];
        $allowed = Access::getUserLembagaIds($userId);
        if (!$row || !in_array((int)$row['lembaga_id'], $allowed, true)) {
            throw new \RuntimeException('Akses lembaga ditolak');
        }
        $stmt = $this->db->prepare('UPDATE proker SET nama=?, deskripsi=?, penanggung_jawab_user_id=?, periode_year=? WHERE id=?');
        $stmt->execute([$data['nama'], $data['deskripsi'] ?? null, (int)($data['pj_user_id'] ?? 0) ?: null, (int)$data['periode_year'], $id]);
    }

    public function deleteProker(int $id): void
    {
        $row = $this->find($id);
        $userId = (int) $_SESSION['user_id'];
        $allowed = Access::getUserLembagaIds($userId);
        if (!$row || !in_array((int)$row['lembaga_id'], $allowed, true)) {
            throw new \RuntimeException('Akses lembaga ditolak');
        }
        $stmt = $this->db->prepare('DELETE FROM proker WHERE id=?');
        $stmt->execute([$id]);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM proker WHERE id=?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}