<?php
namespace App\ProgramKerja\Controllers;

use App\Core\Controller;
use App\Core\Access;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $this->setPageTitle('Program Kerja');
        $lembagaIds = Access::getUserLembagaIds((int) $_SESSION['user_id']);
        $this->render('ProgramKerja', 'dashboard/index', [
            'title' => 'Program Kerja',
            'lembagaAkses' => $lembagaIds,
        ]);
    }
}