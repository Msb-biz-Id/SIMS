<?php
namespace App\SistemSurat\Controllers;

use App\Core\Controller;

final class LaporanController extends Controller
{
    public function agenda(): void
    {
        $this->setPageTitle('SIMS - Laporan Agenda');
        $this->render('SistemSurat', 'laporan/agenda', [
            'title' => 'Laporan Agenda',
        ]);
    }
}