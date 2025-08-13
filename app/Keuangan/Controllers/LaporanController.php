<?php
namespace App\Keuangan\Controllers;

use App\Core\Controller;

final class LaporanController extends Controller
{
    public function index(): void
    {
        $this->setPageTitle('Keuangan - Laporan');
        $this->render('Keuangan', 'laporan/index', [
            'title' => 'Laporan Keuangan',
        ]);
    }
}