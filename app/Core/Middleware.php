<?php
namespace App\Core;

use App\SistemDataMaster\Models\Role;

final class Middleware
{
    public static function handle(array $middlewares): void
    {
        foreach ($middlewares as $mw) {
            if ($mw === 'auth') {
                if (empty($_SESSION['user_id'])) {
                    header('Location: ' . base_url('auth/login'));
                    exit;
                }
                continue;
            }
            if (str_starts_with($mw, 'role:')) {
                $role = substr($mw, 5);
                $userId = (int) ($_SESSION['user_id'] ?? 0);
                if ($userId <= 0 || !(new Role())->userHasRole($userId, $role)) {
                    http_response_code(403);
                    exit('Akses ditolak (role)');
                }
                continue;
            }
            if ($mw === 'scope:keuangan') {
                if (empty(Access::getUserKeuanganLembagaIds((int) ($_SESSION['user_id'] ?? 0)))) {
                    http_response_code(403);
                    exit('Akses ditolak (scope keuangan)');
                }
                continue;
            }
        }
    }
}