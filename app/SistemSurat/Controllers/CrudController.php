<?php
namespace App\SistemSurat\Controllers;

use App\Core\Controller;
use App\SistemSurat\Models\Surat;
use App\SistemSurat\Models\Lampiran;

final class CrudController extends Controller
{
    public function create(): void
    {
        $this->requireAuth();
        $type = $_GET['type'] ?? 'masuk';
        $this->setPageTitle('Tambah Surat ' . ucfirst($type));
        $this->render('SistemSurat', 'surat/create', ['type' => $type]);
    }

    public function store(): void
    {
        $this->requireAuth();
        if (!verify_csrf()) { flash('error', 'Sesi kadaluarsa'); $this->redirect('sims/surat-masuk'); }
        $data = [
            'tipe' => $_POST['tipe'] ?? 'masuk',
            'lembaga_id' => (int) ($_POST['lembaga_id'] ?? 0),
            'tanggal' => $_POST['tanggal'] ?? date('Y-m-d'),
            'klasifikasi_kode' => $_POST['klasifikasi_kode'] ?? null,
            'perihal' => trim($_POST['perihal'] ?? ''),
            'ringkas' => trim($_POST['ringkas'] ?? ''),
            'pengirim' => trim($_POST['pengirim'] ?? ''),
            'penerima' => trim($_POST['penerima'] ?? ''),
            'created_by' => (int) $_SESSION['user_id'],
        ];
        if ($data['perihal'] === '' || $data['lembaga_id'] <= 0) {
            flash('error', 'Perihal dan Lembaga wajib diisi');
            $this->redirect('sims/surat-masuk');
        }
        $id = (new Surat())->create($data);
        flash('success', 'Surat dibuat');
        $this->redirect($data['tipe'] === 'masuk' ? 'sims/surat-masuk' : 'sims/surat-keluar');
    }

    public function edit(): void
    {
        $this->requireAuth();
        $id = (int) ($_GET['id'] ?? 0);
        $surat = (new Surat())->find($id);
        if (!$surat) { flash('error', 'Data tidak ditemukan'); $this->redirect('sims/surat-masuk'); }
        $lampiran = (new Lampiran())->listBySurat($id);
        $this->setPageTitle('Edit Surat ' . ucfirst($surat['tipe']));
        $this->render('SistemSurat', 'surat/edit', compact('surat', 'lampiran'));
    }

    public function update(): void
    {
        $this->requireAuth();
        if (!verify_csrf()) { flash('error', 'Sesi kadaluarsa'); $this->redirect('sims/surat-masuk'); }
        $id = (int) ($_POST['id'] ?? 0);
        $data = [
            'tanggal' => $_POST['tanggal'] ?? date('Y-m-d'),
            'klasifikasi_kode' => $_POST['klasifikasi_kode'] ?? null,
            'perihal' => trim($_POST['perihal'] ?? ''),
            'ringkas' => trim($_POST['ringkas'] ?? ''),
            'pengirim' => trim($_POST['pengirim'] ?? ''),
            'penerima' => trim($_POST['penerima'] ?? ''),
        ];
        (new Surat())->updateSurat($id, $data);
        flash('success', 'Surat diperbarui');
        $this->redirect('sims/surat-edit?id=' . $id);
    }

    public function delete(): void
    {
        $this->requireAuth();
        if (!verify_csrf()) { flash('error', 'Sesi kadaluarsa'); $this->redirect('sims/surat-masuk'); }
        $id = (int) ($_POST['id'] ?? 0);
        (new Surat())->deleteSurat($id);
        flash('success', 'Surat dihapus');
        $this->redirect('sims/surat-masuk');
    }

    public function uploadLampiran(): void
    {
        $this->requireAuth();
        if (!verify_csrf()) { flash('error', 'Sesi kadaluarsa'); $this->redirect('sims/surat-masuk'); }
        $suratId = (int) ($_POST['surat_id'] ?? 0);
        if (empty($_FILES['file']['name'])) { flash('error', 'Tidak ada file'); $this->redirect('sims/surat-edit?id=' . $suratId); }
        $allowed = ['image/jpeg','image/png','application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['file']['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, $allowed, true)) { flash('error', 'Tipe file tidak diizinkan'); $this->redirect('sims/surat-edit?id=' . $suratId); }
        if ($_FILES['file']['size'] > 5 * 1024 * 1024) { flash('error', 'Maksimal 5MB'); $this->redirect('sims/surat-edit?id=' . $suratId); }
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $safe = sanitize_filename(pathinfo($_FILES['file']['name'], PATHINFO_FILENAME));
        $newName = $safe . '-' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
        $target = __DIR__ . '/../../../public/uploads/surat/' . $newName;
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $target)) { flash('error', 'Gagal upload'); $this->redirect('sims/surat-edit?id=' . $suratId); }
        (new Lampiran())->add($suratId, 'uploads/surat/' . $newName, $_FILES['file']['name'], $mime, (int) $_FILES['file']['size']);
        flash('success', 'Lampiran ditambah');
        $this->redirect('sims/surat-edit?id=' . $suratId);
    }

    public function deleteLampiran(): void
    {
        $this->requireAuth();
        if (!verify_csrf()) { flash('error', 'Sesi kadaluarsa'); $this->redirect('sims/surat-masuk'); }
        $id = (int) ($_POST['id'] ?? 0);
        $lamp = (new Lampiran())->find($id);
        if ($lamp) {
            @unlink(__DIR__ . '/../../../public/' . $lamp['file_path']);
            (new Lampiran())->deleteLampiran($id);
        }
        flash('success', 'Lampiran dihapus');
        $this->redirect('sims/surat-masuk');
    }
}