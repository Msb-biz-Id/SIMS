<?php
namespace App\Keuangan\Controllers;

use App\Core\Controller;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $this->setPageTitle('Keuangan - Manajemen Keuangan');
        $this->render('Keuangan', 'dashboard/index', [
            'title' => 'Manajemen Keuangan',
        ]);
    }
}