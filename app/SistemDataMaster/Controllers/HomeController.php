<?php
namespace App\SistemDataMaster\Controllers;

use App\Core\Controller;

final class HomeController extends Controller
{
    public function index(): void
    {
        $this->setPageTitle('Dashboard');
        $this->render('SistemDataMaster', 'home/index', [
            'title' => 'Dashboard',
        ]);
    }
}