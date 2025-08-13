<?php
namespace App\SistemSurat\Models;

use App\Core\Model;
use PDO;

final class Klasifikasi extends Model
{
    public function all(): array
    {
        return $this->db->query('SELECT kode, nama FROM klasifikasi_surat ORDER BY kode ASC')->fetchAll();
    }

    public function upsert(string $kode, string $nama): void
    {
        $stmt = $this->db->prepare('INSERT INTO klasifikasi_surat(kode, nama) VALUES(?,?) ON DUPLICATE KEY UPDATE nama=VALUES(nama)');
        $stmt->execute([$kode, $nama]);
    }
}