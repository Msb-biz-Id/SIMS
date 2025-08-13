<?php
namespace App\SistemSurat\Models;

use App\Core\Model;
use PDO;

final class Lampiran extends Model
{
    public function listBySurat(int $suratId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM surat_lampiran WHERE surat_id=? ORDER BY id ASC');
        $stmt->execute([$suratId]);
        return $stmt->fetchAll();
    }

    public function add(int $suratId, string $filePath, string $originalName, string $mime, int $size): int
    {
        $stmt = $this->db->prepare('INSERT INTO surat_lampiran(surat_id, file_path, original_name, mime, size) VALUES(?,?,?,?,?)');
        $stmt->execute([$suratId, $filePath, $originalName, $mime, $size]);
        return (int) $this->db->lastInsertId();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM surat_lampiran WHERE id=?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function deleteLampiran(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM surat_lampiran WHERE id=?');
        $stmt->execute([$id]);
    }
}