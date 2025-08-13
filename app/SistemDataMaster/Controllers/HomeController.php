<?php
namespace App\SistemDataMaster\Controllers;

use App\Core\Controller;

final class HomeController extends Controller
{
    public function index(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('auth/login');
        }
        $this->setPageTitle('Dashboard');
        $this->render('SistemDataMaster', 'home/index', [
            'title' => 'Dashboard',
        ]);
    }
}