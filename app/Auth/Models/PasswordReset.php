<?php
namespace App\Auth\Models;

use App\Core\Model;
use DateTimeImmutable;
use PDO;

final class PasswordReset extends Model
{
    public function createToken(int $userId, string $token, DateTimeImmutable $expiresAt): void
    {
        $hash = hash('sha256', $token);
        $stmt = $this->db->prepare('INSERT INTO password_resets(user_id, token_hash, expires_at) VALUES(?,?,?)');
        $stmt->execute([$userId, $hash, $expiresAt->format('Y-m-d H:i:s')]);
    }

    public function findValid(string $token): ?array
    {
        $hash = hash('sha256', $token);
        $stmt = $this->db->prepare('SELECT * FROM password_resets WHERE token_hash=? AND used=0 AND expires_at > NOW() LIMIT 1');
        $stmt->execute([$hash]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function markUsed(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE password_resets SET used=1 WHERE id=?');
        $stmt->execute([$id]);
    }
}