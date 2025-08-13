<?php
namespace App\ProgramKerja\Controllers;

use App\Core\Controller;
use App\Core\Access;
use App\ProgramKerja\Models\Proker;
use App\SistemDataMaster\Models\Lembaga;

final class ProkerController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $lembagaAkses = Access::getUserLembagaIds((int) $_SESSION['user_id']);
        if (empty($lembagaAkses)) { http_response_code(403); exit('Akses ditolak'); }
        $filters = [
            'lembaga_id' => $_GET['lembaga_id'] ?? null,
            'periode_year' => $_GET['periode_year'] ?? null,
        ];
        $rows = (new Proker())->list($filters, 1000, 0);
        // hydrate name map for filter options
        $lembagaOptions = array_values((new Lembaga())->getByIds($lembagaAkses));
        $this->setPageTitle('Program Kerja');
        $this->render('ProgramKerja', 'proker/index', compact('rows','lembagaAkses','filters','lembagaOptions'));
    }

    public function create(): void
    {
        $this->requireAuth();
        $lembagaAkses = Access::getUserLembagaIds((int) $_SESSION['user_id']);
        if (empty($lembagaAkses)) { http_response_code(403); exit('Akses ditolak'); }
        $lembagaOptions = array_values((new Lembaga())->getByIds($lembagaAkses));
        $this->setPageTitle('Tambah Program Kerja');
        $this->render('ProgramKerja', 'proker/create', compact('lembagaAkses','lembagaOptions'));
    }

    public function store(): void
    {
        $this->requireAuth();
        if (!verify_csrf()) { flash('error', 'Sesi kadaluarsa'); $this->redirect('program-kerja/proker'); }
        $data = [
            'lembaga_id' => (int) ($_POST['lembaga_id'] ?? 0),
            'nama' => trim($_POST['nama'] ?? ''),
            'deskripsi' => $_POST['deskripsi'] ?? null,
            'pj_user_id' => (int) ($_POST['pj_user_id'] ?? 0) ?: null,
            'periode_year' => (int) ($_POST['periode_year'] ?? date('Y')),
        ];
        try { (new Proker())->create($data); flash('success','Program kerja ditambahkan'); }
        catch (\Throwable $e) { flash('error', $e->getMessage()); }
        $this->redirect('program-kerja/proker');
    }

    public function edit(): void
    {
        $this->requireAuth();
        $id = (int) ($_GET['id'] ?? 0);
        $model = new Proker();
        $row = $model->find($id);
        if (!$row) { flash('error', 'Data tidak ditemukan'); $this->redirect('program-kerja/proker'); }
        $lembagaAkses = Access::getUserLembagaIds((int) $_SESSION['user_id']);
        if (!in_array((int)$row['lembaga_id'], $lembagaAkses, true) && !Access::isSuperAdmin((int)$_SESSION['user_id'])) {
            http_response_code(403); exit('Akses ditolak');
        }
        // hydrate lembaga_name
        $lm = (new Lembaga())->findById((int)$row['lembaga_id']);
        if ($lm) { $row['lembaga_name'] = $lm['name']; }
        $this->setPageTitle('Edit Program Kerja');
        $this->render('ProgramKerja', 'proker/edit', compact('row'));
    }

    public function update(): void
    {
        $this->requireAuth();
        if (!verify_csrf()) { flash('error', 'Sesi kadaluarsa'); $this->redirect('program-kerja/proker'); }
        $id = (int) ($_POST['id'] ?? 0);
        $data = [
            'nama' => trim($_POST['nama'] ?? ''),
            'deskripsi' => $_POST['deskripsi'] ?? null,
            'pj_user_id' => (int) ($_POST['pj_user_id'] ?? 0) ?: null,
            'periode_year' => (int) ($_POST['periode_year'] ?? date('Y')),
        ];
        try { (new Proker())->updateProker($id, $data); flash('success','Program kerja diperbarui'); }
        catch (\Throwable $e) { flash('error', $e->getMessage()); }
        $this->redirect('program-kerja/proker');
    }

    public function delete(): void
    {
        $this->requireAuth();
        if (!verify_csrf()) { flash('error', 'Sesi kadaluarsa'); $this->redirect('program-kerja/proker'); }
        $id = (int) ($_POST['id'] ?? 0);
        try { (new Proker())->deleteProker($id); flash('success', 'Program kerja dihapus'); }
        catch (\Throwable $e) { flash('error', $e->getMessage()); }
        $this->redirect('program-kerja/proker');
    }
}