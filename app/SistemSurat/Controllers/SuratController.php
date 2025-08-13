<?php
namespace App\SistemSurat\Controllers;

use App\Core\Controller;

final class SuratController extends Controller
{
    public function masuk(): void
    {
        $this->setPageTitle('SIMS - Surat Masuk');
        $this->render('SistemSurat', 'surat/masuk', [
            'title' => 'Surat Masuk',
        ]);
    }

    public function keluar(): void
    {
        $this->setPageTitle('SIMS - Surat Keluar');
        $this->render('SistemSurat', 'surat/keluar', [
            'title' => 'Surat Keluar',
        ]);
    }
}