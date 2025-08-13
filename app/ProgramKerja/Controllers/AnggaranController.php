<?php
namespace App\ProgramKerja\Controllers;

use App\Core\Controller;

final class AnggaranController extends Controller
{
    public function index(): void
    {
        $this->setPageTitle('Program Kerja - Manajemen Anggaran');
        $this->render('ProgramKerja', 'anggaran/index', [
            'title' => 'Manajemen Anggaran',
        ]);
    }
}