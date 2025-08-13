<?php
namespace App\SistemDataMaster\Models;

use App\Core\Model;
use PDO;

final class User extends Model
{
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function all(int $limit = 100, int $offset = 0): array
    {
        $stmt = $this->db->prepare('SELECT * FROM users ORDER BY id DESC LIMIT ? OFFSET ?');
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create(string $name, string $email, string $passwordHash, ?string $avatarPath = null): int
    {
        $stmt = $this->db->prepare('INSERT INTO users(name, email, password_hash, avatar_path) VALUES(?,?,?,?)');
        $stmt->execute([$name, $email, $passwordHash, $avatarPath]);
        return (int) $this->db->lastInsertId();
    }

    public function updateUser(int $id, string $name, string $email, ?string $passwordHash = null, ?string $avatarPath = null): void
    {
        if ($passwordHash !== null && $avatarPath !== null) {
            $stmt = $this->db->prepare('UPDATE users SET name=?, email=?, password_hash=?, avatar_path=? WHERE id=?');
            $stmt->execute([$name, $email, $passwordHash, $avatarPath, $id]);
            return;
        }
        if ($passwordHash !== null) {
            $stmt = $this->db->prepare('UPDATE users SET name=?, email=?, password_hash=? WHERE id=?');
            $stmt->execute([$name, $email, $passwordHash, $id]);
            return;
        }
        if ($avatarPath !== null) {
            $stmt = $this->db->prepare('UPDATE users SET name=?, email=?, avatar_path=? WHERE id=?');
            $stmt->execute([$name, $email, $avatarPath, $id]);
            return;
        }
        $stmt = $this->db->prepare('UPDATE users SET name=?, email=? WHERE id=?');
        $stmt->execute([$name, $email, $id]);
    }

    public function deleteUser(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id=?');
        $stmt->execute([$id]);
    }
}