<?php
namespace App\SistemSurat\Models;

use App\Core\Model;
use App\Core\Access;
use PDO;
use DateTimeImmutable;

final class Surat extends Model
{
    public function list(string $tipe, array $filters = [], int $limit = 100, int $offset = 0): array
    {
        $where = ['tipe = :tipe'];
        $params = [':tipe' => $tipe];
        if (!empty($filters['lembaga_id'])) { $where[] = 'lembaga_id = :lembaga_id'; $params[':lembaga_id'] = (int)$filters['lembaga_id']; }
        if (!empty($filters['tahun'])) { $where[] = 'tahun = :tahun'; $params[':tahun'] = (int)$filters['tahun']; }
        if (!empty($filters['tgl_from'])) { $where[] = 'tanggal >= :tgl_from'; $params[':tgl_from'] = $filters['tgl_from']; }
        if (!empty($filters['tgl_to'])) { $where[] = 'tanggal <= :tgl_to'; $params[':tgl_to'] = $filters['tgl_to']; }
        if (!empty($filters['klasifikasi'])) { $where[] = 'klasifikasi_kode = :klas'; $params[':klas'] = $filters['klasifikasi']; }

        // Batasan lembaga berdasarkan kepemilikan kecuali superadmin
        $userId = (int) ($_SESSION['user_id'] ?? 0);
        if ($userId && !Access::isSuperAdmin($userId) && empty($filters['lembaga_id'])) {
            // filter hanya pada lembaga yang user miliki
            $where[] = 'lembaga_id IN (SELECT lembaga_id FROM user_lembaga WHERE user_id = :uid)';
            $params[':uid'] = $userId;
        }

        $sql = 'SELECT * FROM surat WHERE ' . implode(' AND ', $where) . ' ORDER BY tanggal DESC, id DESC LIMIT :limit OFFSET :offset';
        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) { $stmt->bindValue($k, $v); }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $lembagaId = (int) $data['lembaga_id'];
        $nomor = $this->resolveNomorSurat($lembagaId, (string)$data['tipe']);
        $tahun = (int) (new DateTimeImmutable($data['tanggal']))->format('Y');
        $stmt = $this->db->prepare('INSERT INTO surat(tipe, lembaga_id, nomor_surat, tanggal, klasifikasi_kode, perihal, ringkas, pengirim, penerima, tahun, created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute([
            $data['tipe'], $lembagaId, $nomor, $data['tanggal'], $data['klasifikasi_kode'] ?? null, $data['perihal'], $data['ringkas'] ?? null, $data['pengirim'] ?? null, $data['penerima'] ?? null, $tahun, (int) $data['created_by']
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM surat WHERE id=?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function updateSurat(int $id, array $data): void
    {
        $stmt = $this->db->prepare('UPDATE surat SET tanggal=?, klasifikasi_kode=?, perihal=?, ringkas=?, pengirim=?, penerima=? WHERE id=?');
        $stmt->execute([$data['tanggal'], $data['klasifikasi_kode'] ?? null, $data['perihal'], $data['ringkas'] ?? null, $data['pengirim'] ?? null, $data['penerima'] ?? null, $id]);
    }

    public function deleteSurat(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM surat WHERE id=?');
        $stmt->execute([$id]);
    }

    private function resolveNomorSurat(int $lembagaId, string $tipe): string
    {
        $row = $this->db->prepare('SELECT surat_nomor_mode, surat_nomor_counter, surat_nomor_year, surat_nomor_prefix FROM lembaga WHERE id=?');
        $row->execute([$lembagaId]);
        $cfg = $row->fetch();
        $year = (int) date('Y');
        if (!$cfg || $cfg['surat_nomor_mode'] === 'statis') {
            return ($cfg['surat_nomor_prefix'] ? $cfg['surat_nomor_prefix'] . '/' : '') . 'MANUAL/' . $year;
        }
        $counter = (int) $cfg['surat_nomor_counter'];
        $lastYear = (int) $cfg['surat_nomor_year'];
        if ($lastYear !== $year) { $counter = 0; $lastYear = $year; }
        $counter++;
        $upd = $this->db->prepare('UPDATE lembaga SET surat_nomor_counter=?, surat_nomor_year=? WHERE id=?');
        $upd->execute([$counter, $lastYear, $lembagaId]);
        $prefix = $cfg['surat_nomor_prefix'] ? $cfg['surat_nomor_prefix'] . '/' : '';
        return sprintf('%s%04d/%s/%d', $prefix, $counter, strtoupper($tipe), $year);
    }
}