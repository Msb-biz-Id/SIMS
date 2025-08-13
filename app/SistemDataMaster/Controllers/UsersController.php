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

    public function import(): void
    {
        $this->requireRole('admin');
        $this->setPageTitle('Import Users');
        $this->render('SistemDataMaster', 'users/import');
    }

    public function doImport(): void
    {
        $this->requireRole('admin');
        if (!verify_csrf()) { flash('error', 'Sesi kadaluarsa'); $this->redirect('users/import'); }
        if (empty($_FILES['file']['name'])) { flash('error', 'File tidak dipilih'); $this->redirect('users/import'); }
        if (!class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
            flash('error', 'PhpSpreadsheet belum terpasang');
            $this->redirect('users/import');
        }
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['file']['tmp_name']);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            $userModel = new User();
            $roleModel = new Role();
            foreach ($rows as $i => $row) {
                if ($i === 0) { continue; } // header
                [$name, $email, $password, $rolesCsv] = $row;
                if (!$name || !$email || !$password) { continue; }
                $userId = $userModel->create((string)$name, (string)$email, password_hash((string)$password, PASSWORD_DEFAULT), null);
                $roleNames = array_filter(array_map('trim', explode(',', (string)$rolesCsv ?: '')));
                if ($roleNames) {
                    $all = $roleModel->all();
                    $map = [];
                    foreach ($all as $r) { $map[$r['name']] = (int) $r['id']; }
                    $ids = [];
                    foreach ($roleNames as $rn) { if (isset($map[$rn])) { $ids[] = $map[$rn]; } }
                    if ($ids) { $roleModel->assignRoles($userId, $ids); }
                }
            }
            flash('success', 'Import users selesai');
        } catch (\Throwable $e) {
            flash('error', 'Gagal import: ' . $e->getMessage());
        }
        $this->redirect('users');
    }

    public function export(): void
    {
        $this->requireRole('admin');
        $users = (new User())->all(10000);
        // Fallback CSV jika PhpSpreadsheet tidak tersedia
        if (!class_exists(\PhpOffice\PhpSpreadsheet\Spreadsheet::class)) {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="users.csv"');
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Name', 'Email']);
            foreach ($users as $u) { fputcsv($out, [$u['name'], $u['email']]); }
            fclose($out);
            exit;
        }
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(['Name', 'Email'], null, 'A1');
        $row = 2;
        foreach ($users as $u) {
            $sheet->setCellValue('A' . $row, $u['name']);
            $sheet->setCellValue('B' . $row, $u['email']);
            $row++;
        }
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="users.xlsx"');
        $writer->save('php://output');
        exit;
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
        $target = __DIR__ . '/../../../public/uploads/avatars/' . $newName;
        if (!move_uploaded_file($file['tmp_name'], $target)) {
            throw new \RuntimeException('Gagal menyimpan file');
        }
        return 'uploads/avatars/' . $newName;
    }
}