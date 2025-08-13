<?php
namespace App\SistemSurat\Controllers;

use App\Core\Controller;

final class SuratController extends Controller
{
    public function masuk(): void
    {
        $this->setPageTitle('SIMS - Surat Masuk');
        $this->render('SistemSurat', 'surat/masuk', [
            'title' => 'Surat Masuk',
        ]);
    }

    public function keluar(): void
    {
        $this->setPageTitle('SIMS - Surat Keluar');
        $this->render('SistemSurat', 'surat/keluar', [
            'title' => 'Surat Keluar',
        ]);
    }

    public function exportCsv(): void
    {
        $this->requireAuth();
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="surat.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Tanggal', 'Nomor', 'Perihal']);
        // TODO: fetch real data
        fputcsv($out, ['2025-01-01', '001/A', 'Contoh surat']);
        fclose($out);
        exit;
    }

    public function exportPdf(): void
    {
        $this->requireAuth();
        if (!class_exists(\Dompdf\Dompdf::class)) {
            exit('Dompdf tidak terpasang');
        }
        $html = '<h3>Daftar Surat</h3><p>Contoh</p>';
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream('surat.pdf');
        exit;
    }
}