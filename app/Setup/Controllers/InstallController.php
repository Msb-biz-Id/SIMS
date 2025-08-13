<?php
namespace App\Setup\Controllers;

use App\Core\Controller;
use App\Core\Database;
use PDO;

final class InstallController extends Controller
{
    public function run(): void
    {
        $this->requireDev();
        $db = Database::connection();
        $db->beginTransaction();
        try {
            $this->createUsersTable($db);
            $this->createRolesTables($db);
            $this->createLembagaTable($db);
            $this->createUserLembagaTable($db);
            $this->createSettingsTable($db);
            $this->createPasswordResetsTable($db);
            $this->createSuratTables($db);
            $this->createProgramKerjaTables($db);
            $this->createKeuanganTables($db);
            $this->alterLembagaForNomor($db);
            $this->seedDefaults($db);
            $db->commit();
            echo 'Installation complete. Default admin: admin@example.com / admin123';
        } catch (\Throwable $e) {
            $db->rollBack();
            http_response_code(500);
            echo 'Install failed: ' . $e->getMessage();
        }
    }

    private function requireDev(): void
    {
        $env = getenv('APP_ENV') ?: 'development';
        if ($env !== 'development') {
            http_response_code(403);
            exit('Installer disabled in non-development environment');
        }
    }

    private function createUsersTable(PDO $db): void
    {
        $db->exec('CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(150) NOT NULL,
            email VARCHAR(190) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            avatar_path VARCHAR(255) NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
    }

    private function createRolesTables(PDO $db): void
    {
        $db->exec('CREATE TABLE IF NOT EXISTS roles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL UNIQUE,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');

        $db->exec('CREATE TABLE IF NOT EXISTS user_roles (
            user_id INT NOT NULL,
            role_id INT NOT NULL,
            PRIMARY KEY(user_id, role_id),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
    }

    private function createLembagaTable(PDO $db): void
    {
        $db->exec('CREATE TABLE IF NOT EXISTS lembaga (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(150) NOT NULL UNIQUE,
            description TEXT NULL,
            is_keuangan TINYINT(1) NOT NULL DEFAULT 0,
            parent_id INT NULL,
            logo_path VARCHAR(255) NULL,
            surat_nomor_mode VARCHAR(10) NOT NULL DEFAULT "statis",
            surat_nomor_counter INT NOT NULL DEFAULT 0,
            surat_nomor_year INT NOT NULL DEFAULT 0,
            surat_nomor_prefix VARCHAR(50) NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (parent_id) REFERENCES lembaga(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
    }

    private function createUserLembagaTable(PDO $db): void
    {
        $db->exec('CREATE TABLE IF NOT EXISTS user_lembaga (
            user_id INT NOT NULL,
            lembaga_id INT NOT NULL,
            PRIMARY KEY(user_id, lembaga_id),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (lembaga_id) REFERENCES lembaga(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
    }

    private function createSettingsTable(PDO $db): void
    {
        $db->exec('CREATE TABLE IF NOT EXISTS settings (
            `key` VARCHAR(100) NOT NULL PRIMARY KEY,
            `value` TEXT NULL,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
    }

    private function alterLembagaForNomor(PDO $db): void
    {
        // Kolom sudah dibuat pada createLembagaTable
    }

    private function createPasswordResetsTable(PDO $db): void
    {
        $db->exec('CREATE TABLE IF NOT EXISTS password_resets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            token_hash CHAR(64) NOT NULL,
            expires_at DATETIME NOT NULL,
            used TINYINT(1) NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY token_hash_unique (token_hash),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
    }

    private function createSuratTables(PDO $db): void
    {
        $db->exec("CREATE TABLE IF NOT EXISTS klasifikasi_surat (
            id INT AUTO_INCREMENT PRIMARY KEY,
            kode VARCHAR(20) NOT NULL UNIQUE,
            nama VARCHAR(190) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $db->exec("CREATE TABLE IF NOT EXISTS surat (
            id INT AUTO_INCREMENT PRIMARY KEY,
            tipe ENUM('masuk','keluar') NOT NULL,
            lembaga_id INT NOT NULL,
            nomor_surat VARCHAR(100) NOT NULL,
            tanggal DATE NOT NULL,
            klasifikasi_kode VARCHAR(20) NULL,
            perihal VARCHAR(255) NOT NULL,
            ringkas TEXT NULL,
            pengirim VARCHAR(190) NULL,
            penerima VARCHAR(190) NULL,
            tahun INT NOT NULL,
            created_by INT NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (lembaga_id) REFERENCES lembaga(id) ON DELETE RESTRICT,
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $db->exec("CREATE TABLE IF NOT EXISTS surat_lampiran (
            id INT AUTO_INCREMENT PRIMARY KEY,
            surat_id INT NOT NULL,
            file_path VARCHAR(255) NOT NULL,
            original_name VARCHAR(190) NOT NULL,
            mime VARCHAR(100) NOT NULL,
            size INT NOT NULL,
            uploaded_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (surat_id) REFERENCES surat(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    private function createKeuanganTables(PDO $db): void
    {
        $db->exec('CREATE TABLE IF NOT EXISTS keu_transaksi (
            id INT AUTO_INCREMENT PRIMARY KEY,
            lembaga_id INT NOT NULL,
            proker_id INT NULL,
            tanggal DATE NOT NULL,
            jenis ENUM("masuk","keluar") NOT NULL,
            kategori VARCHAR(100) NULL,
            nominal DECIMAL(18,2) NOT NULL,
            keterangan TEXT NULL,
            created_by INT NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (lembaga_id) REFERENCES lembaga(id) ON DELETE RESTRICT,
            FOREIGN KEY (proker_id) REFERENCES proker(id) ON DELETE SET NULL,
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT,
            INDEX idx_keu_filter (lembaga_id, tanggal, jenis)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
        // ensure column exists if table already created
        $db->exec('ALTER TABLE keu_transaksi ADD COLUMN IF NOT EXISTS proker_id INT NULL');
        $db->exec('ALTER TABLE keu_transaksi ADD CONSTRAINT IF NOT EXISTS fk_keu_proker FOREIGN KEY (proker_id) REFERENCES proker(id) ON DELETE SET NULL');
    }

    private function createProgramKerjaTables(PDO $db): void
    {
        $db->exec('CREATE TABLE IF NOT EXISTS proker (
            id INT AUTO_INCREMENT PRIMARY KEY,
            lembaga_id INT NOT NULL,
            nama VARCHAR(190) NOT NULL,
            deskripsi TEXT NULL,
            penanggung_jawab_user_id INT NULL,
            periode_year INT NOT NULL,
            created_by INT NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (lembaga_id) REFERENCES lembaga(id) ON DELETE RESTRICT,
            FOREIGN KEY (penanggung_jawab_user_id) REFERENCES users(id) ON DELETE SET NULL,
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');

        $db->exec('CREATE TABLE IF NOT EXISTS proker_anggaran (
            id INT AUTO_INCREMENT PRIMARY KEY,
            proker_id INT NOT NULL,
            alokasi DECIMAL(18,2) NOT NULL,
            terpakai DECIMAL(18,2) NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (proker_id) REFERENCES proker(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
    }

    private function seedDefaults(PDO $db): void
    {
        $roles = ['admin', 'manager', 'staff'];
        $stmt = $db->prepare('INSERT IGNORE INTO roles(name) VALUES (?)');
        foreach ($roles as $r) {
            $stmt->execute([$r]);
        }
        $email = 'admin@example.com';
        $check = $db->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $check->execute([$email]);
        $userId = $check->fetchColumn();
        if (!$userId) {
            $hash = password_hash('admin123', PASSWORD_DEFAULT);
            $ins = $db->prepare('INSERT INTO users(name, email, password_hash) VALUES (?,?,?)');
            $ins->execute(['Administrator', $email, $hash]);
            $userId = (int) $db->lastInsertId();
        }
        $roleId = (int) $db->query("SELECT id FROM roles WHERE name='admin' LIMIT 1")->fetchColumn();
        if ($roleId && $userId) {
            $db->prepare('INSERT IGNORE INTO user_roles(user_id, role_id) VALUES (?,?)')->execute([$userId, $roleId]);
        }
    }
}