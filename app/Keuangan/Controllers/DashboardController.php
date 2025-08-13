<?php
namespace App\Keuangan\Controllers;

use App\Core\Controller;
use App\Core\Access;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $userId = (int) $_SESSION['user_id'];
        $keuLembagaIds = Access::getUserKeuanganLembagaIds($userId);
        if (empty($keuLembagaIds)) {
            http_response_code(403);
            exit('Akses ditolak: tidak ada lembaga keuangan yang Anda kelola');
        }
        $this->setPageTitle('Keuangan - Manajemen Keuangan');
        $this->render('Keuangan', 'dashboard/index', [
            'title' => 'Manajemen Keuangan',
            'lembagaAkses' => $keuLembagaIds,
        ]);
    }
}