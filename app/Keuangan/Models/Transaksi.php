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
        [$in, $inParams] = sql_in_clause(array_map('intval', $allowed), 'lid');
        $where = ['kt.lembaga_id IN (' . $in . ')'];
        $params = $inParams;
        if (!empty($filters['lembaga_id'])) { $where[] = 'kt.lembaga_id = :lembaga_id'; $params[':lembaga_id'] = (int)$filters['lembaga_id']; }
        if (!empty($filters['jenis'])) { $where[] = 'kt.jenis = :jenis'; $params[':jenis'] = $filters['jenis']; }
        if (!empty($filters['tgl_from'])) { $where[] = 'kt.tanggal >= :tgl_from'; $params[':tgl_from'] = $filters['tgl_from']; }
        if (!empty($filters['tgl_to'])) { $where[] = 'kt.tanggal <= :tgl_to'; $params[':tgl_to'] = $filters['tgl_to']; }
        $sql = 'SELECT kt.*, l.name AS lembaga_name, p.nama AS proker_nama FROM keu_transaksi kt JOIN lembaga l ON l.id=kt.lembaga_id LEFT JOIN proker p ON p.id=kt.proker_id WHERE ' . implode(' AND ', $where) . ' ORDER BY kt.tanggal DESC, kt.id DESC LIMIT :limit OFFSET :offset';
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
        $stmt = $this->db->prepare('INSERT INTO keu_transaksi(lembaga_id, proker_id, tanggal, jenis, kategori, nominal, keterangan, created_by) VALUES(?,?,?,?,?,?,?,?)');
        $stmt->execute([(int)$data['lembaga_id'], $data['proker_id'] ?? null, $data['tanggal'], $data['jenis'], $data['kategori'] ?? null, (float)$data['nominal'], $data['keterangan'] ?? null, $userId]);
        $id = (int) $this->db->lastInsertId();
        $this->syncProkerTerpakai($data['proker_id'] ?? null);
        return $id;
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
        $this->syncProkerTerpakai($row['proker_id'] ?? null);
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
        $this->syncProkerTerpakai($row['proker_id'] ?? null);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM keu_transaksi WHERE id=?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function saldo(array $filters = []): float
    {
        $userId = (int) ($_SESSION['user_id'] ?? 0);
        $allowed = Access::getUserKeuanganLembagaIds($userId);
        if (empty($allowed)) { return 0.0; }
        [$in, $inParams] = sql_in_clause(array_map('intval', $allowed), 'lid');
        $where = ['lembaga_id IN (' . $in . ')'];
        $params = $inParams;
        if (!empty($filters['lembaga_id'])) { $where[] = 'lembaga_id = :lembaga_id'; $params[':lembaga_id'] = (int)$filters['lembaga_id']; }
        if (!empty($filters['tgl_from'])) { $where[] = 'tanggal >= :tgl_from'; $params[':tgl_from'] = $filters['tgl_from']; }
        if (!empty($filters['tgl_to'])) { $where[] = 'tanggal <= :tgl_to'; $params[':tgl_to'] = $filters['tgl_to']; }
        $sql = 'SELECT SUM(CASE WHEN jenis="masuk" THEN nominal ELSE -nominal END) AS saldo FROM keu_transaksi WHERE ' . implode(' AND ', $where);
        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) { $stmt->bindValue($k, $v); }
        $stmt->execute();
        return (float) ($stmt->fetchColumn() ?: 0);
    }

    public function laporanLabaRugi(array $filters = []): array
    {
        $userId = (int) ($_SESSION['user_id'] ?? 0);
        $allowed = Access::getUserKeuanganLembagaIds($userId);
        if (empty($allowed)) { return ['pendapatan'=>0,'pengeluaran'=>0,'laba_rugi'=>0]; }
        [$in, $inParams] = sql_in_clause(array_map('intval', $allowed), 'lid');
        $where = ['lembaga_id IN (' . $in . ')'];
        $params = $inParams;
        if (!empty($filters['lembaga_id'])) { $where[] = 'lembaga_id = :lembaga_id'; $params[':lembaga_id'] = (int)$filters['lembaga_id']; }
        if (!empty($filters['tgl_from'])) { $where[] = 'tanggal >= :tgl_from'; $params[':tgl_from'] = $filters['tgl_from']; }
        if (!empty($filters['tgl_to'])) { $where[] = 'tanggal <= :tgl_to'; $params[':tgl_to'] = $filters['tgl_to']; }
        $sql = 'SELECT jenis, SUM(nominal) total FROM keu_transaksi WHERE ' . implode(' AND ', $where) . ' GROUP BY jenis';
        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) { $stmt->bindValue($k, $v); }
        $stmt->execute();
        $pendapatan = 0.0; $pengeluaran = 0.0;
        foreach ($stmt->fetchAll() as $r) {
            if ($r['jenis'] === 'masuk') { $pendapatan = (float) $r['total']; }
            else { $pengeluaran = (float) $r['total']; }
        }
        return ['pendapatan'=>$pendapatan, 'pengeluaran'=>$pengeluaran, 'laba_rugi'=>$pendapatan - $pengeluaran];
    }

    public function laporanArusKas(array $filters = []): array
    {
        $userId = (int) ($_SESSION['user_id'] ?? 0);
        $allowed = Access::getUserKeuanganLembagaIds($userId);
        if (empty($allowed)) { return []; }
        [$in, $inParams] = sql_in_clause(array_map('intval', $allowed), 'lid');
        $where = ['lembaga_id IN (' . $in . ')'];
        $params = $inParams;
        if (!empty($filters['lembaga_id'])) { $where[] = 'lembaga_id = :lembaga_id'; $params[':lembaga_id'] = (int)$filters['lembaga_id']; }
        if (!empty($filters['tgl_from'])) { $where[] = 'tanggal >= :tgl_from'; $params[':tgl_from'] = $filters['tgl_from']; }
        if (!empty($filters['tgl_to'])) { $where[] = 'tanggal <= :tgl_to'; $params[':tgl_to'] = $filters['tgl_to']; }
        $sql = 'SELECT tanggal, jenis, nominal FROM keu_transaksi WHERE ' . implode(' AND ', $where) . ' ORDER BY tanggal ASC, id ASC';
        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) { $stmt->bindValue($k, $v); }
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $saldo = 0.0; $arus = [];
        foreach ($rows as $r) {
            $saldo += ($r['jenis'] === 'masuk') ? (float)$r['nominal'] : -(float)$r['nominal'];
            $arus[] = ['tanggal'=>$r['tanggal'],'jenis'=>$r['jenis'],'nominal'=>(float)$r['nominal'],'saldo'=>$saldo];
        }
        return $arus;
    }

    private function syncProkerTerpakai($prokerId): void
    {
        if (!$prokerId) { return; }
        $stmt = $this->db->prepare('SELECT SUM(nominal) FROM keu_transaksi WHERE proker_id=? AND jenis="keluar"');
        $stmt->execute([(int)$prokerId]);
        $terpakai = (float) ($stmt->fetchColumn() ?: 0);
        $upd = $this->db->prepare('UPDATE proker_anggaran SET terpakai=? WHERE proker_id=?');
        $upd->execute([$terpakai, (int)$prokerId]);
    }
}