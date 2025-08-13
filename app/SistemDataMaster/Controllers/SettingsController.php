<?php
namespace App\SistemDataMaster\Controllers;

use App\Core\Controller;
use App\Core\Settings;

final class SettingsController extends Controller
{
    public function index(): void
    {
        $this->requireRole('admin');
        $this->setPageTitle('Pengaturan Sistem');
        $data = [
            'site_title' => Settings::get('site_title', 'Platform MST'),
            'site_description' => Settings::get('site_description', ''),
            'logo_path' => Settings::get('site_logo', ''),
            'favicon_path' => Settings::get('site_favicon', ''),
            'turnstile_site_key' => Settings::get('turnstile_site_key', ''),
            'turnstile_secret_key' => Settings::get('turnstile_secret_key', ''),
        ];
        $this->render('SistemDataMaster', 'settings/index', $data);
    }

    public function save(): void
    {
        $this->requireRole('admin');
        if (!verify_csrf()) { flash('error', 'Sesi kadaluarsa'); $this->redirect('settings'); }
        Settings::set('site_title', trim($_POST['site_title'] ?? ''));
        Settings::set('site_description', trim($_POST['site_description'] ?? ''));
        Settings::set('turnstile_site_key', trim($_POST['turnstile_site_key'] ?? ''));
        Settings::set('turnstile_secret_key', trim($_POST['turnstile_secret_key'] ?? ''));

        // handle logo upload
        if (!empty($_FILES['site_logo']['name'])) {
            $path = $this->handleUpload($_FILES['site_logo'], ['image/png','image/jpeg','image/svg+xml'], 'uploads/settings');
            Settings::set('site_logo', $path);
        }
        if (!empty($_FILES['site_favicon']['name'])) {
            $path = $this->handleUpload($_FILES['site_favicon'], ['image/png','image/x-icon','image/vnd.microsoft.icon'], 'uploads/settings');
            Settings::set('site_favicon', $path);
        }
        flash('success', 'Pengaturan disimpan');
        $this->redirect('settings');
    }

    private function handleUpload(array $file, array $allowed, string $dir): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) { throw new \RuntimeException('Upload gagal'); }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = (string) finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, $allowed, true)) { throw new \RuntimeException('Tipe file tidak diizinkan'); }
        if ($file['size'] > 2 * 1024 * 1024) { throw new \RuntimeException('Maks 2MB'); }
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $safe = sanitize_filename(pathinfo($file['name'], PATHINFO_FILENAME));
        $newName = $safe . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
        $targetDir = __DIR__ . '/../../../public/' . $dir;
        if (!is_dir($targetDir)) { mkdir($targetDir, 0777, true); }
        $target = $targetDir . '/' . $newName;
        if (!move_uploaded_file($file['tmp_name'], $target)) { throw new \RuntimeException('Gagal simpan file'); }
        return $dir . '/' . $newName;
    }
}