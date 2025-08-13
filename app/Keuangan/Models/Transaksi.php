<?php
namespace App\Keuangan\Models;

use App\Core\Model;
use App\Core\Access;
use PDO;

final class Transaksi extends Model
{
    public function list(array $filters, int $limit = 100, int $offset = 0): array
    {
        $userId = (int) ($_SESSION['user_id'] ?? 0);
        $allowed = Access::getUserKeuanganLembagaIds($userId);
        if (empty($allowed)) { return []; }
        $where = ['lembaga_id IN (' . implode(',', array_map('intval', $allowed)) . ')'];
        $params = [];
        if (!empty($filters['lembaga_id'])) { $where[] = 'lembaga_id = :lembaga_id'; $params[':lembaga_id'] = (int)$filters['lembaga_id']; }
        if (!empty($filters['jenis'])) { $where[] = 'jenis = :jenis'; $params[':jenis'] = $filters['jenis']; }
        if (!empty($filters['tgl_from'])) { $where[] = 'tanggal >= :tgl_from'; $params[':tgl_from'] = $filters['tgl_from']; }
        if (!empty($filters['tgl_to'])) { $where[] = 'tanggal <= :tgl_to'; $params[':tgl_to'] = $filters['tgl_to']; }
        $sql = 'SELECT * FROM keu_transaksi WHERE ' . implode(' AND ', $where) . ' ORDER BY tanggal DESC, id DESC LIMIT :limit OFFSET :offset';
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
        $allowed = Access::getUserKeuanganLembagaIds($userId);
        if (!in_array((int)$data['lembaga_id'], $allowed, true)) {
            throw new \RuntimeException('Akses lembaga ditolak');
        }
        $stmt = $this->db->prepare('INSERT INTO keu_transaksi(lembaga_id, tanggal, jenis, kategori, nominal, keterangan, created_by) VALUES(?,?,?,?,?,?,?)');
        $stmt->execute([(int)$data['lembaga_id'], $data['tanggal'], $data['jenis'], $data['kategori'] ?? null, (float)$data['nominal'], $data['keterangan'] ?? null, $userId]);
        return (int) $this->db->lastInsertId();
    }

    public function updateTransaksi(int $id, array $data): void
    {
        $row = $this->find($id);
        $userId = (int) $_SESSION['user_id'];
        $allowed = Access::getUserKeuanganLembagaIds($userId);
        if (!$row || !in_array((int)$row['lembaga_id'], $allowed, true)) {
            throw new \RuntimeException('Akses lembaga ditolak');
        }
        $stmt = $this->db->prepare('UPDATE keu_transaksi SET tanggal=?, jenis=?, kategori=?, nominal=?, keterangan=? WHERE id=?');
        $stmt->execute([$data['tanggal'], $data['jenis'], $data['kategori'] ?? null, (float)$data['nominal'], $data['keterangan'] ?? null, $id]);
    }

    public function deleteTransaksi(int $id): void
    {
        $row = $this->find($id);
        $userId = (int) $_SESSION['user_id'];
        $allowed = Access::getUserKeuanganLembagaIds($userId);
        if (!$row || !in_array((int)$row['lembaga_id'], $allowed, true)) {
            throw new \RuntimeException('Akses lembaga ditolak');
        }
        $stmt = $this->db->prepare('DELETE FROM keu_transaksi WHERE id=?');
        $stmt->execute([$id]);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM keu_transaksi WHERE id=?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}