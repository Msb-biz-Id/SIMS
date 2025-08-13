<?php
namespace App\SistemDataMaster\Controllers;

use App\Core\Controller;
use App\SistemDataMaster\Models\User;
use App\SistemDataMaster\Models\Role;

final class RolesController extends Controller
{
    public function index(): void
    {
        $this->requireRole('admin');
        $users = (new User())->all(500);
        $roleModel = new Role();
        $userRoles = [];
        foreach ($users as $u) {
            $userRoles[$u['id']] = $roleModel->getUserRoleNames((int)$u['id']);
        }
        $this->setPageTitle('Roles & Assignment');
        $this->render('SistemDataMaster', 'roles/index', compact('users', 'userRoles'));
    }

    public function assign(): void
    {
        $this->requireRole('admin');
        $userId = (int) ($_GET['id'] ?? 0);
        $user = (new User())->findById($userId);
        if (!$user) {
            flash('error', 'User tidak ditemukan');
            $this->redirect('roles');
        }
        $roleModel = new Role();
        $roles = $roleModel->all();
        $userRoleNames = $roleModel->getUserRoleNames($userId);
        $this->setPageTitle('Assign Roles');
        $this->render('SistemDataMaster', 'roles/assign', compact('user', 'roles', 'userRoleNames'));
    }

    public function saveAssignment(): void
    {
        $this->requireRole('admin');
        if (!verify_csrf()) {
            flash('error', 'Sesi kadaluarsa');
            $this->redirect('roles');
        }
        $userId = (int) ($_POST['user_id'] ?? 0);
        $roleIds = array_map('intval', $_POST['roles'] ?? []);
        (new Role())->assignRoles($userId, $roleIds);
        flash('success', 'Role assignment tersimpan');
        $this->redirect('roles');
    }
}