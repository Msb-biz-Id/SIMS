<?php
namespace App\ProgramKerja\Controllers;

use App\Core\Controller;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $this->setPageTitle('Program Kerja');
        $this->render('ProgramKerja', 'dashboard/index', [
            'title' => 'Program Kerja',
        ]);
    }
}