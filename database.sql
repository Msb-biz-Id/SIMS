-- Database schema for Platform Multi-Sistem Terintegrasi
-- Target: MySQL 8.x (InnoDB, utf8mb4)

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Optional: uncomment and adjust database name
-- CREATE DATABASE IF NOT EXISTS `platform_mst` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
-- USE `platform_mst`;

DROP TABLE IF EXISTS `surat_lampiran`;
DROP TABLE IF EXISTS `surat`;
DROP TABLE IF EXISTS `klasifikasi_surat`;
DROP TABLE IF EXISTS `password_resets`;
DROP TABLE IF EXISTS `user_roles`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `user_lembaga`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `keu_transaksi`;
DROP TABLE IF EXISTS `proker_anggaran`;
DROP TABLE IF EXISTS `proker`;
DROP TABLE IF EXISTS `lembaga`;

CREATE TABLE `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `avatar_path` VARCHAR(255) NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `settings` (
  `key` VARCHAR(100) NOT NULL,
  `value` TEXT NULL,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `roles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_roles_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `user_lembaga` (
  `user_id` INT NOT NULL,
  `lembaga_id` INT NOT NULL,
  PRIMARY KEY (`user_id`, `lembaga_id`),
  KEY `idx_ul_lembaga` (`lembaga_id`),
  CONSTRAINT `fk_ul_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ul_lembaga` FOREIGN KEY (`lembaga_id`) REFERENCES `lembaga` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `user_roles` (
  `user_id` INT NOT NULL,
  `role_id` INT NOT NULL,
  PRIMARY KEY (`user_id`, `role_id`),
  KEY `idx_user_roles_role` (`role_id`),
  CONSTRAINT `fk_user_roles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_roles_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lembaga` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `description` TEXT NULL,
  `is_keuangan` TINYINT(1) NOT NULL DEFAULT 0,
  `parent_id` INT NULL,
  `logo_path` VARCHAR(255) NULL,
  `surat_nomor_mode` VARCHAR(10) NOT NULL DEFAULT 'statis',
  `surat_nomor_counter` INT NOT NULL DEFAULT 0,
  `surat_nomor_year` INT NOT NULL DEFAULT 0,
  `surat_nomor_prefix` VARCHAR(50) NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_lembaga_name` (`name`),
  KEY `idx_lembaga_parent` (`parent_id`),
  CONSTRAINT `fk_lembaga_parent` FOREIGN KEY (`parent_id`) REFERENCES `lembaga` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_resets` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `token_hash` CHAR(64) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `used` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_password_resets_token` (`token_hash`),
  KEY `idx_password_resets_user` (`user_id`),
  CONSTRAINT `fk_password_resets_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `klasifikasi_surat` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `kode` VARCHAR(20) NOT NULL,
  `nama` VARCHAR(190) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_klasifikasi_kode` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `surat` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tipe` ENUM('masuk','keluar') NOT NULL,
  `lembaga_id` INT NOT NULL,
  `nomor_surat` VARCHAR(100) NOT NULL,
  `tanggal` DATE NOT NULL,
  `klasifikasi_kode` VARCHAR(20) NULL,
  `perihal` VARCHAR(255) NOT NULL,
  `ringkas` TEXT NULL,
  `pengirim` VARCHAR(190) NULL,
  `penerima` VARCHAR(190) NULL,
  `tahun` INT NOT NULL,
  `created_by` INT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_surat_filters` (`tipe`, `lembaga_id`, `tahun`, `tanggal`),
  KEY `idx_surat_klasifikasi` (`klasifikasi_kode`),
  CONSTRAINT `fk_surat_lembaga` FOREIGN KEY (`lembaga_id`) REFERENCES `lembaga` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_surat_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_surat_klasifikasi` FOREIGN KEY (`klasifikasi_kode`) REFERENCES `klasifikasi_surat` (`kode`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `surat_lampiran` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `surat_id` INT NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `original_name` VARCHAR(190) NOT NULL,
  `mime` VARCHAR(100) NOT NULL,
  `size` INT NOT NULL,
  `uploaded_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_lampiran_surat` (`surat_id`),
  CONSTRAINT `fk_lampiran_surat` FOREIGN KEY (`surat_id`) REFERENCES `surat` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `proker` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `lembaga_id` INT NOT NULL,
  `nama` VARCHAR(190) NOT NULL,
  `deskripsi` TEXT NULL,
  `penanggung_jawab_user_id` INT NULL,
  `periode_year` INT NOT NULL,
  `created_by` INT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_proker_filters` (`lembaga_id`, `periode_year`),
  CONSTRAINT `fk_proker_lembaga` FOREIGN KEY (`lembaga_id`) REFERENCES `lembaga` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_proker_pj_user` FOREIGN KEY (`penanggung_jawab_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_proker_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `proker_anggaran` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `proker_id` INT NOT NULL,
  `alokasi` DECIMAL(18,2) NOT NULL,
  `terpakai` DECIMAL(18,2) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_anggaran_proker` (`proker_id`),
  CONSTRAINT `fk_anggaran_proker` FOREIGN KEY (`proker_id`) REFERENCES `proker` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `keu_transaksi` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `lembaga_id` INT NOT NULL,
  `proker_id` INT NULL,
  `tanggal` DATE NOT NULL,
  `jenis` ENUM('masuk','keluar') NOT NULL,
  `kategori` VARCHAR(100) NULL,
  `nominal` DECIMAL(18,2) NOT NULL,
  `keterangan` TEXT NULL,
  `created_by` INT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_keu_filter` (`lembaga_id`, `tanggal`, `jenis`),
  CONSTRAINT `fk_keu_lembaga` FOREIGN KEY (`lembaga_id`) REFERENCES `lembaga` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_keu_proker` FOREIGN KEY (`proker_id`) REFERENCES `proker` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_keu_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- Seed minimal data (roles)
INSERT INTO `roles` (`name`) VALUES
  ('admin'),
  ('manager'),
  ('staff')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Optional seed admin user (ganti password_hash sesuai lingkungan).
-- Contoh password hash (bcrypt) untuk admin123 perlu dibuat di lingkungan Anda.
-- INSERT INTO `users` (`name`, `email`, `password_hash`) VALUES ('Administrator', 'admin@example.com', '$2y$10$REPLACE_WITH_BCRYPT_HASH');
-- INSERT INTO `user_roles` (`user_id`, `role_id`) VALUES (1, (SELECT id FROM roles WHERE name='admin'));