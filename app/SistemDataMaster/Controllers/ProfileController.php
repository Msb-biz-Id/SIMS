<?php
namespace App\SistemDataMaster\Controllers;

use App\Core\Controller;
use App\SistemDataMaster\Models\User;

final class ProfileController extends Controller
{
    public function view(): void
    {
        $this->requireAuth();
        $user = (new User())->findById((int) $_SESSION['user_id']);
        $this->setPageTitle('Profil Saya');
        $this->render('SistemDataMaster', 'profile/view', compact('user'));
    }

    public function edit(): void
    {
        $this->requireAuth();
        $user = (new User())->findById((int) $_SESSION['user_id']);
        $this->setPageTitle('Edit Profil');
        $this->render('SistemDataMaster', 'profile/edit', compact('user'));
    }

    public function update(): void
    {
        $this->requireAuth();
        if (!verify_csrf()) { flash('error', 'Sesi kadaluarsa'); $this->redirect('profile/edit'); }
        $id = (int) $_SESSION['user_id'];
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $avatarPath = null;
        if (!empty($_FILES['avatar']['name'])) {
            $avatarPath = $this->handleAvatarUpload($_FILES['avatar']);
        }
        $passwordHash = $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : null;
        (new User())->updateUser($id, $name, $email, $passwordHash, $avatarPath);
        flash('success', 'Profil diperbarui');
        $this->redirect('profile');
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