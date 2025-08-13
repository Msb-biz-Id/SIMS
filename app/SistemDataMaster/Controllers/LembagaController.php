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

        // Simpan setting penomoran bila ada
        $mode = $_POST['surat_nomor_mode'] ?? null;
        $prefix = $_POST['surat_nomor_prefix'] ?? null;
        $counter = isset($_POST['surat_nomor_counter']) ? (int) $_POST['surat_nomor_counter'] : null;
        if ($mode !== null || $prefix !== null || $counter !== null) {
            $sql = 'UPDATE lembaga SET ' .
                   'surat_nomor_mode = COALESCE(?, surat_nomor_mode),' .
                   'surat_nomor_prefix = ?,' .
                   'surat_nomor_counter = COALESCE(?, surat_nomor_counter) WHERE id=?';
            $stmt = (new Lembaga())::getStaticDb()->prepare($sql);
            $stmt->execute([$mode, $prefix, $counter, $id]);
        }

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

    public function import(): void
    {
        $this->requireRole('admin');
        $this->setPageTitle('Import Lembaga');
        $this->render('SistemDataMaster', 'lembaga/import');
    }

    public function doImport(): void
    {
        $this->requireRole('admin');
        if (!verify_csrf()) { flash('error', 'Sesi kadaluarsa'); $this->redirect('lembaga/import'); }
        if (empty($_FILES['file']['name'])) { flash('error', 'File tidak dipilih'); $this->redirect('lembaga/import'); }
        if (!class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
            flash('error', 'PhpSpreadsheet belum terpasang');
            $this->redirect('lembaga/import');
        }
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['file']['tmp_name']);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            $model = new Lembaga();
            foreach ($rows as $i => $row) {
                if ($i === 0) { continue; }
                [$name, $description, $isKeuangan, $parentId] = $row;
                if (!$name) { continue; }
                $model->create((string)$name, (string)($description ?: ''), (bool)$isKeuangan, $parentId !== '' ? (int)$parentId : null, null);
            }
            flash('success', 'Import lembaga selesai');
        } catch (\Throwable $e) {
            flash('error', 'Gagal import: ' . $e->getMessage());
        }
        $this->redirect('lembaga');
    }

    public function export(): void
    {
        $this->requireRole('admin');
        $items = (new Lembaga())->all();
        if (!class_exists(\PhpOffice\PhpSpreadsheet\Spreadsheet::class)) {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="lembaga.csv"');
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Name', 'Description', 'Is Keuangan', 'Parent ID']);
            foreach ($items as $it) { fputcsv($out, [$it['name'], $it['description'], $it['is_keuangan'], $it['parent_id']]); }
            fclose($out);
            exit;
        }
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(['Name', 'Description', 'Is Keuangan', 'Parent ID'], null, 'A1');
        $row = 2;
        foreach ($items as $it) {
            $sheet->fromArray([$it['name'], $it['description'], $it['is_keuangan'], $it['parent_id']], null, 'A' . $row);
            $row++;
        }
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="lembaga.xlsx"');
        $writer->save('php://output');
        exit;
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