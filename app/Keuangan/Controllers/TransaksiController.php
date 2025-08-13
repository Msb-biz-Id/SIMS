<?php
namespace App\Keuangan\Controllers;

use App\Core\Controller;
use App\Core\Access;
use App\Keuangan\Models\Transaksi;
use App\SistemDataMaster\Models\Lembaga;
use App\ProgramKerja\Models\Proker;

final class TransaksiController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $userId = (int) $_SESSION['user_id'];
        $lembagaAkses = Access::getUserKeuanganLembagaIds($userId);
        if (empty($lembagaAkses)) { http_response_code(403); exit('Akses ditolak'); }
        $filters = [
            'lembaga_id' => $_GET['lembaga_id'] ?? null,
            'jenis' => $_GET['jenis'] ?? null,
            'tgl_from' => $_GET['tgl_from'] ?? null,
            'tgl_to' => $_GET['tgl_to'] ?? null,
        ];
        $rows = (new Transaksi())->list($filters, 1000, 0);
        $lembagaOptions = array_values((new Lembaga())->getByIds($lembagaAkses));
        $this->setPageTitle('Transaksi Keuangan');
        $this->render('Keuangan', 'transaksi/index', compact('rows','lembagaAkses','filters','lembagaOptions'));
    }

    public function create(): void
    {
        $this->requireAuth();
        $lembagaAkses = Access::getUserKeuanganLembagaIds((int) $_SESSION['user_id']);
        if (empty($lembagaAkses)) { http_response_code(403); exit('Akses ditolak'); }
        $lembagaOptions = array_values((new Lembaga())->getByIds($lembagaAkses));
        // Sederhana: daftar proker dari semua lembaga akses (bisa difilter client-side setelah lembaga dipilih)
        $prokerOptions = [];
        $prokerModel = new Proker();
        foreach ($lembagaAkses as $lid) {
            foreach ($prokerModel->listByLembaga((int)$lid) as $p) { $prokerOptions[] = $p + ['lembaga_id' => (int)$lid]; }
        }
        $this->setPageTitle('Tambah Transaksi');
        $this->render('Keuangan', 'transaksi/create', compact('lembagaAkses','lembagaOptions','prokerOptions'));
    }

    public function store(): void
    {
        $this->requireAuth();
        if (!verify_csrf()) { flash('error', 'Sesi kadaluarsa'); $this->redirect('keuangan/transaksi'); }
        $data = [
            'lembaga_id' => (int) ($_POST['lembaga_id'] ?? 0),
            'proker_id' => (int) ($_POST['proker_id'] ?? 0) ?: null,
            'tanggal' => $_POST['tanggal'] ?? date('Y-m-d'),
            'jenis' => $_POST['jenis'] ?? 'masuk',
            'kategori' => $_POST['kategori'] ?? null,
            'nominal' => (float) ($_POST['nominal'] ?? 0),
            'keterangan' => $_POST['keterangan'] ?? null,
        ];
        try {
            (new Transaksi())->create($data);
            flash('success', 'Transaksi ditambahkan');
        } catch (\Throwable $e) {
            flash('error', $e->getMessage());
        }
        $this->redirect('keuangan/transaksi');
    }

    public function edit(): void
    {
        $this->requireAuth();
        $id = (int) ($_GET['id'] ?? 0);
        $model = new Transaksi();
        $row = $model->find($id);
        if (!$row) { flash('error', 'Data tidak ditemukan'); $this->redirect('keuangan/transaksi'); }
        $lembagaAkses = Access::getUserKeuanganLembagaIds((int) $_SESSION['user_id']);
        if (!in_array((int)$row['lembaga_id'], $lembagaAkses, true) && !Access::isSuperAdmin((int)$_SESSION['user_id'])) {
            http_response_code(403); exit('Akses ditolak');
        }
        // hydrate lembaga name
        $lm = (new Lembaga())->findById((int)$row['lembaga_id']);
        if ($lm) { $row['lembaga_name'] = $lm['name']; }
        // daftar proker untuk lembaga bersangkutan
        $prokerOptions = (new Proker())->listByLembaga((int)$row['lembaga_id']);
        $this->setPageTitle('Edit Transaksi');
        $this->render('Keuangan', 'transaksi/edit', compact('row','lembagaAkses','prokerOptions'));
    }

    public function update(): void
    {
        $this->requireAuth();
        if (!verify_csrf()) { flash('error', 'Sesi kadaluarsa'); $this->redirect('keuangan/transaksi'); }
        $id = (int) ($_POST['id'] ?? 0);
        $data = [
            'tanggal' => $_POST['tanggal'] ?? date('Y-m-d'),
            'jenis' => $_POST['jenis'] ?? 'masuk',
            'kategori' => $_POST['kategori'] ?? null,
            'nominal' => (float) ($_POST['nominal'] ?? 0),
            'keterangan' => $_POST['keterangan'] ?? null,
        ];
        try {
            (new Transaksi())->updateTransaksi($id, $data);
            flash('success', 'Transaksi diperbarui');
        } catch (\Throwable $e) {
            flash('error', $e->getMessage());
        }
        $this->redirect('keuangan/transaksi');
    }

    public function delete(): void
    {
        $this->requireAuth();
        if (!verify_csrf()) { flash('error', 'Sesi kadaluarsa'); $this->redirect('keuangan/transaksi'); }
        $id = (int) ($_POST['id'] ?? 0);
        try {
            (new Transaksi())->deleteTransaksi($id);
            flash('success', 'Transaksi dihapus');
        } catch (\Throwable $e) {
            flash('error', $e->getMessage());
        }
        $this->redirect('keuangan/transaksi');
    }
}