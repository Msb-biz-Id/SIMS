<?php
namespace App\SistemSurat\Controllers;

use App\Core\Controller;
use App\SistemSurat\Models\Surat as SuratModel;

final class SuratController extends Controller
{
    public function masuk(): void
    {
        $this->requireAuth();
        $this->setPageTitle('SIMS - Surat Masuk');
        $filters = [
            'lembaga_id' => $_GET['lembaga_id'] ?? null,
            'tahun' => $_GET['tahun'] ?? null,
            'tgl_from' => $_GET['tgl_from'] ?? null,
            'tgl_to' => $_GET['tgl_to'] ?? null,
            'klasifikasi' => $_GET['klasifikasi'] ?? null,
        ];
        $surat = (new SuratModel())->list('masuk', $filters, 1000, 0);
        $this->render('SistemSurat', 'surat/masuk', [
            'title' => 'Surat Masuk',
            'filters' => $filters,
            'surat' => $surat,
        ]);
    }

    public function keluar(): void
    {
        $this->requireAuth();
        $this->setPageTitle('SIMS - Surat Keluar');
        $filters = [
            'lembaga_id' => $_GET['lembaga_id'] ?? null,
            'tahun' => $_GET['tahun'] ?? null,
            'tgl_from' => $_GET['tgl_from'] ?? null,
            'tgl_to' => $_GET['tgl_to'] ?? null,
            'klasifikasi' => $_GET['klasifikasi'] ?? null,
        ];
        $surat = (new SuratModel())->list('keluar', $filters, 1000, 0);
        $this->render('SistemSurat', 'surat/keluar', [
            'title' => 'Surat Keluar',
            'filters' => $filters,
            'surat' => $surat,
        ]);
    }

    public function exportCsv(): void
    {
        $this->requireAuth();
        $filters = [
            'lembaga_id' => $_GET['lembaga_id'] ?? null,
            'tahun' => $_GET['tahun'] ?? null,
            'tgl_from' => $_GET['tgl_from'] ?? null,
            'tgl_to' => $_GET['tgl_to'] ?? null,
            'klasifikasi' => $_GET['klasifikasi'] ?? null,
        ];
        $tipe = $_GET['tipe'] ?? 'masuk';
        $rows = (new SuratModel())->list($tipe, $filters, 10000, 0);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="surat-' . $tipe . '.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Tanggal', 'Nomor', 'Perihal', 'Klasifikasi', 'Pengirim', 'Penerima']);
        foreach ($rows as $r) {
            fputcsv($out, [$r['tanggal'], $r['nomor_surat'], $r['perihal'], $r['klasifikasi_kode'], $r['pengirim'], $r['penerima']]);
        }
        fclose($out);
        exit;
    }

    public function exportPdf(): void
    {
        $this->requireAuth();
        if (!class_exists(\Dompdf\Dompdf::class)) {
            exit('Dompdf tidak terpasang');
        }
        $filters = [
            'lembaga_id' => $_GET['lembaga_id'] ?? null,
            'tahun' => $_GET['tahun'] ?? null,
            'tgl_from' => $_GET['tgl_from'] ?? null,
            'tgl_to' => $_GET['tgl_to'] ?? null,
            'klasifikasi' => $_GET['klasifikasi'] ?? null,
        ];
        $tipe = $_GET['tipe'] ?? 'masuk';
        $rows = (new SuratModel())->list($tipe, $filters, 10000, 0);
        $html = '<h3>Daftar Surat ' . htmlspecialchars(strtoupper($tipe)) . '</h3>';
        $html .= '<table width="100%" border="1" cellspacing="0" cellpadding="4"><tr><th>Tanggal</th><th>Nomor</th><th>Perihal</th><th>Klasifikasi</th><th>Pengirim</th><th>Penerima</th></tr>';
        foreach ($rows as $r) {
            $html .= '<tr><td>' . htmlspecialchars($r['tanggal']) . '</td><td>' . htmlspecialchars($r['nomor_surat']) . '</td><td>' . htmlspecialchars($r['perihal']) . '</td><td>' . htmlspecialchars((string)$r['klasifikasi_kode']) . '</td><td>' . htmlspecialchars((string)$r['pengirim']) . '</td><td>' . htmlspecialchars((string)$r['penerima']) . '</td></tr>';
        }
        $html .= '</table>';
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream('surat-' . $tipe . '.pdf');
        exit;
    }
}