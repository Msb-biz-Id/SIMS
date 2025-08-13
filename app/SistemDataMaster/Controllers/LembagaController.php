<?php
namespace App\SistemDataMaster\Controllers;

use App\Core\Controller;
use App\SistemDataMaster\Models\Lembaga;

final class LembagaController extends Controller
{
    public function index(): void
    {
        $this->requireRole('admin');
        $items = (new Lembaga())->all();
        $this->setPageTitle('Lembaga');
        $this->render('SistemDataMaster', 'lembaga/index', compact('items'));
    }

    public function create(): void
    {
        $this->requireRole('admin');
        $this->setPageTitle('Tambah Lembaga');
        $this->render('SistemDataMaster', 'lembaga/create');
    }

    public function store(): void
    {
        $this->requireRole('admin');
        if (!verify_csrf()) { flash('error', 'Sesi kadaluarsa'); $this->redirect('lembaga/create'); }
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '') ?: null;
        $isKeuangan = isset($_POST['is_keuangan']);
        $parentId = !empty($_POST['parent_id']) ? (int) $_POST['parent_id'] : null;
        $logoPath = null;
        if (!empty($_FILES['logo']['name'])) {
            $logoPath = $this->handleLogoUpload($_FILES['logo']);
        }
        (new Lembaga())->create($name, $description, $isKeuangan, $parentId, $logoPath);
        flash('success', 'Lembaga dibuat');
        $this->redirect('lembaga');
    }

    public function edit(): void
    {
        $this->requireRole('admin');
        $id = (int) ($_GET['id'] ?? 0);
        $item = (new Lembaga())->findById($id);
        if (!$item) { flash('error', 'Data tidak ditemukan'); $this->redirect('lembaga'); }
        $this->setPageTitle('Edit Lembaga');
        $this->render('SistemDataMaster', 'lembaga/edit', compact('item'));
    }

    public function update(): void
    {
        $this->requireRole('admin');
        if (!verify_csrf()) { flash('error', 'Sesi kadaluarsa'); $this->redirect('lembaga'); }
        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '') ?: null;
        $isKeuangan = isset($_POST['is_keuangan']);
        $parentId = !empty($_POST['parent_id']) ? (int) $_POST['parent_id'] : null;
        $logoPath = null;
        if (!empty($_FILES['logo']['name'])) {
            $logoPath = $this->handleLogoUpload($_FILES['logo']);
        }
        (new Lembaga())->updateLembaga($id, $name, $description, $isKeuangan, $parentId, $logoPath);
        flash('success', 'Lembaga diperbarui');
        $this->redirect('lembaga');
    }

    public function delete(): void
    {
        $this->requireRole('admin');
        if (!verify_csrf()) { flash('error', 'Sesi kadaluarsa'); $this->redirect('lembaga'); }
        $id = (int) ($_POST['id'] ?? 0);
        (new Lembaga())->deleteLembaga($id);
        flash('success', 'Lembaga dihapus');
        $this->redirect('lembaga');
    }

    private function handleLogoUpload(array $file): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Upload logo gagal');
        }
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!isset($allowed[$mime])) { throw new \RuntimeException('Tipe file tidak diizinkan'); }
        if ($file['size'] > 2 * 1024 * 1024) { throw new \RuntimeException('File terlalu besar (maks 2MB)'); }
        $ext = $allowed[$mime];
        $safeName = sanitize_filename(pathinfo($file['name'], PATHINFO_FILENAME));
        $newName = $safeName . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
        $targetDir = __DIR__ . '/../../../public/uploads/lembaga';
        if (!is_dir($targetDir)) { mkdir($targetDir, 0777, true); }
        $target = $targetDir . '/' . $newName;
        if (!move_uploaded_file($file['tmp_name'], $target)) { throw new \RuntimeException('Gagal menyimpan file'); }
        return 'uploads/lembaga/' . $newName;
    }
}