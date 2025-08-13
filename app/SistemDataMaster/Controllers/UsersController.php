<?php
namespace App\SistemDataMaster\Controllers;

use App\Core\Controller;
use App\SistemDataMaster\Models\User;
use App\SistemDataMaster\Models\Role;

final class UsersController extends Controller
{
    public function index(): void
    {
        $this->requireRole('admin');
        $userModel = new User();
        $users = $userModel->all();
        $this->setPageTitle('Users');
        $this->render('SistemDataMaster', 'users/index', compact('users'));
    }

    public function create(): void
    {
        $this->requireRole('admin');
        $roles = (new Role())->all();
        $this->setPageTitle('Create User');
        $this->render('SistemDataMaster', 'users/create', compact('roles'));
    }

    public function store(): void
    {
        $this->requireRole('admin');
        if (!verify_csrf()) {
            flash('error', 'Sesi kadaluarsa');
            $this->redirect('users/create');
        }
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $roleIds = array_map('intval', $_POST['roles'] ?? []);

        if ($name === '' || $email === '' || $password === '') {
            flash('error', 'Nama, email, password wajib diisi');
            $this->redirect('users/create');
        }

        $avatarPath = null;
        if (!empty($_FILES['avatar']['name'])) {
            $avatarPath = $this->handleAvatarUpload($_FILES['avatar']);
        }

        $userModel = new User();
        $userId = $userModel->create($name, $email, password_hash($password, PASSWORD_DEFAULT), $avatarPath);
        (new Role())->assignRoles($userId, $roleIds);
        flash('success', 'User berhasil dibuat');
        $this->redirect('users');
    }

    public function edit(): void
    {
        $this->requireRole('admin');
        $id = (int) ($_GET['id'] ?? 0);
        $userModel = new User();
        $roleModel = new Role();
        $user = $userModel->findById($id);
        if (!$user) {
            flash('error', 'User tidak ditemukan');
            $this->redirect('users');
        }
        $roles = $roleModel->all();
        $userRoleNames = $roleModel->getUserRoleNames($id);
        $this->setPageTitle('Edit User');
        $this->render('SistemDataMaster', 'users/edit', compact('user', 'roles', 'userRoleNames'));
    }

    public function update(): void
    {
        $this->requireRole('admin');
        if (!verify_csrf()) {
            flash('error', 'Sesi kadaluarsa');
            $this->redirect('users');
        }
        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $roleIds = array_map('intval', $_POST['roles'] ?? []);

        $avatarPath = null;
        if (!empty($_FILES['avatar']['name'])) {
            $avatarPath = $this->handleAvatarUpload($_FILES['avatar']);
        }

        $userModel = new User();
        $passwordHash = $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : null;
        $userModel->updateUser($id, $name, $email, $passwordHash, $avatarPath);
        (new Role())->assignRoles($id, $roleIds);
        flash('success', 'User diperbarui');
        $this->redirect('users');
    }

    public function delete(): void
    {
        $this->requireRole('admin');
        if (!verify_csrf()) {
            flash('error', 'Sesi kadaluarsa');
            $this->redirect('users');
        }
        $id = (int) ($_POST['id'] ?? 0);
        (new User())->deleteUser($id);
        flash('success', 'User dihapus');
        $this->redirect('users');
    }

    private function handleAvatarUpload(array $file): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Upload avatar gagal');
        }
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!isset($allowed[$mime])) {
            throw new \RuntimeException('Tipe file tidak diizinkan');
        }
        if ($file['size'] > 2 * 1024 * 1024) {
            throw new \RuntimeException('File terlalu besar (maks 2MB)');
        }
        $ext = $allowed[$mime];
        $safeName = sanitize_filename(pathinfo($file['name'], PATHINFO_FILENAME));
        $newName = $safeName . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
        $target = __DIR__ . '/../../../uploads/avatars/' . $newName;
        if (!move_uploaded_file($file['tmp_name'], $target)) {
            throw new \RuntimeException('Gagal menyimpan file');
        }
        return 'uploads/avatars/' . $newName;
    }
}