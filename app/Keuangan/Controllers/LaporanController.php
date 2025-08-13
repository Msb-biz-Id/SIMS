<?php
namespace App\Keuangan\Controllers;

use App\Core\Controller;
use App\Core\Access;
use App\Keuangan\Models\Transaksi;
use App\SistemDataMaster\Models\Lembaga;

final class LaporanController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $userId = (int) $_SESSION['user_id'];
        $lembagaAkses = Access::getUserKeuanganLembagaIds($userId);
        if (empty($lembagaAkses)) { http_response_code(403); exit('Akses ditolak'); }
        $filters = [
            'lembaga_id' => $_GET['lembaga_id'] ?? null,
            'tgl_from' => $_GET['tgl_from'] ?? null,
            'tgl_to' => $_GET['tgl_to'] ?? null,
        ];
        $model = new Transaksi();
        $saldo = $model->saldo($filters);
        $lr = $model->laporanLabaRugi($filters);
        $arusKas = $model->laporanArusKas($filters);
        $lembagaOptions = array_values((new Lembaga())->getByIds($lembagaAkses));
        $title = 'Laporan Keuangan';
        $this->setPageTitle($title);
        $this->render('Keuangan', 'laporan/index', compact('title','saldo','lr','arusKas','filters','lembagaOptions'));
    }
}