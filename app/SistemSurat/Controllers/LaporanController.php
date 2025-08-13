<?php
namespace App\SistemSurat\Controllers;

use App\Core\Controller;
use App\SistemSurat\Models\Klasifikasi;

final class LaporanController extends Controller
{
    public function agenda(): void
    {
        $this->requireAuth();
        $this->setPageTitle('SIMS - Laporan Agenda');
        $this->render('SistemSurat', 'laporan/agenda', [
            'title' => 'Laporan Agenda',
        ]);
    }

    public function importKlasifikasi(): void
    {
        $this->requireRole('admin');
        $this->setPageTitle('Import Klasifikasi Surat');
        $this->render('SistemSurat', 'laporan/import_klasifikasi');
    }

    public function doImportKlasifikasi(): void
    {
        $this->requireRole('admin');
        if (!verify_csrf()) { flash('error', 'Sesi kadaluarsa'); $this->redirect('sims/import-klasifikasi'); }
        if (empty($_FILES['file']['tmp_name'])) { flash('error', 'File tidak dipilih'); $this->redirect('sims/import-klasifikasi'); }
        $ok = 0; $fail = 0;
        if (($handle = fopen($_FILES['file']['tmp_name'], 'r')) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                if (count($data) < 2) { $fail++; continue; }
                $kode = trim($data[0] ?? '');
                $nama = trim($data[1] ?? '');
                if ($kode === '' || $nama === '') { $fail++; continue; }
                (new Klasifikasi())->upsert($kode, $nama);
                $ok++;
            }
            fclose($handle);
        }
        flash('success', "Import selesai. Berhasil: {$ok}, Gagal: {$fail}");
        $this->redirect('sims/import-klasifikasi');
    }
}