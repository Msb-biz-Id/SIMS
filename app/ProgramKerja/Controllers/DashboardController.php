<?php
namespace App\ProgramKerja\Controllers;

use App\Core\Controller;
use App\Core\Access;
use App\SistemDataMaster\Models\Lembaga;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $this->setPageTitle('Program Kerja');
        $lembagaIds = Access::getUserLembagaIds((int) $_SESSION['user_id']);
        $lembagaOptions = array_values((new Lembaga())->getByIds($lembagaIds));
        $this->render('ProgramKerja', 'dashboard/index', [
            'title' => 'Program Kerja',
            'lembagaAkses' => $lembagaIds,
            'lembagaOptions' => $lembagaOptions,
        ]);
    }
}