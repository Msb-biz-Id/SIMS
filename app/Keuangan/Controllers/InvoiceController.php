<?php
namespace App\Keuangan\Controllers;

use App\Core\Controller;
use App\Core\Access;
use App\Core\Settings;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;
use Dompdf\Dompdf;

final class InvoiceController extends Controller
{
    public function gaji(): void
    {
        $this->requireAuth();
        // guard: hanya user yang punya akses lembaga keuangan
        $allowed = Access::getUserKeuanganLembagaIds((int)($_SESSION['user_id'] ?? 0));
        if (empty($allowed)) { http_response_code(403); exit('Akses ditolak'); }
        $this->setPageTitle('Keuangan - Invoice Gaji');
        $this->render('Keuangan', 'invoice/gaji', [
            'title' => 'Invoice Gaji',
        ]);
    }

    public function send(): void
    {
        $this->requireAuth();
        // guard: hanya user yang punya akses lembaga keuangan
        $allowed = Access::getUserKeuanganLembagaIds((int)($_SESSION['user_id'] ?? 0));
        if (empty($allowed)) { http_response_code(403); exit('Akses ditolak'); }
        if (!verify_csrf()) { flash('error','Sesi kadaluarsa'); $this->redirect('keuangan/invoice-gaji'); }
        $emailsRaw = trim($_POST['emails'] ?? '');
        $subject = trim($_POST['subject'] ?? 'Invoice Gaji');
        $body = trim($_POST['body'] ?? '');
        $attachPdf = isset($_POST['attach_pdf']);
        if ($emailsRaw === '' || $body === '') {
            flash('error','Email tujuan dan isi email wajib diisi');
            $this->redirect('keuangan/invoice-gaji');
        }
        $emails = array_filter(array_map('trim', preg_split('/[\s,;]+/', $emailsRaw)));
        if (empty($emails)) { flash('error','Tidak ada email valid'); $this->redirect('keuangan/invoice-gaji'); }

        $host = Settings::get('smtp_host', getenv('SMTP_HOST') ?: '');
        $port = (int) (Settings::get('smtp_port', getenv('SMTP_PORT') ?: '587') ?: 587);
        $user = Settings::get('smtp_user', getenv('SMTP_USER') ?: '');
        $pass = Settings::get('smtp_pass', getenv('SMTP_PASS') ?: '');
        $secure = Settings::get('smtp_secure', getenv('SMTP_SECURE') ?: 'tls');
        $from = Settings::get('smtp_from', getenv('SMTP_FROM') ?: $user);
        $fromName = Settings::get('smtp_from_name', getenv('SMTP_FROM_NAME') ?: 'Keuangan');

        if (!$host || !$user) {
            flash('error','Konfigurasi SMTP belum lengkap');
            $this->redirect('keuangan/invoice-gaji');
        }

        // Optional: generate PDF from HTML body once and reuse for all recipients
        $pdfData = null;
        $pdfName = 'invoice-gaji-' . date('Ym') . '.pdf';
        if ($attachPdf) {
            $dompdf = new Dompdf();
            $html = '<html><head><meta charset="utf-8"></head><body>' . $body . '</body></html>';
            $dompdf->loadHtml($html, 'UTF-8');
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $pdfData = $dompdf->output();
        }

        $success = 0; $fail = 0; $errors = [];
        foreach ($emails as $to) {
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = $host;
                $mail->SMTPAuth = true;
                $mail->Username = $user;
                $mail->Password = $pass;
                $mail->SMTPSecure = $secure === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = $port;
                $mail->CharSet = 'UTF-8';
                $mail->setFrom($from ?: $user, $fromName ?: 'Keuangan');
                $mail->addAddress($to);
                $mail->Subject = $subject;
                $mail->isHTML(true);
                $mail->Body = $body;
                $mail->AltBody = strip_tags(str_replace(['<br>','<br/>','<br />'], "\n", $body));
                if ($pdfData) {
                    $mail->addStringAttachment($pdfData, $pdfName, 'base64', 'application/pdf');
                }
                $mail->send();
                $success++;
            } catch (MailException $e) {
                $fail++;
                $errors[] = $to . ': ' . $e->getMessage();
            }
        }

        if ($fail === 0) {
            flash('success', 'Invoice terkirim ke ' . $success . ' penerima');
        } else {
            flash('warning', 'Berhasil: ' . $success . ', Gagal: ' . $fail . '. ' . implode(' | ', $errors));
        }
        $this->redirect('keuangan/invoice-gaji');
    }
}