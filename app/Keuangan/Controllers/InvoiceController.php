<?php
namespace App\Keuangan\Controllers;

use App\Core\Controller;
use App\Core\Access;
use App\Core\Settings;
use App\Core\Database;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;
use Dompdf\Dompdf;

final class InvoiceController extends Controller
{
    public function gaji(): void
    {
        $this->requireAuth();
        $allowed = Access::getUserKeuanganLembagaIds((int)($_SESSION['user_id'] ?? 0));
        if (empty($allowed)) { http_response_code(403); exit('Akses ditolak'); }
        $db = Database::connection();
        $lembagaOptions = [];
        if (!empty($allowed)) {
            [$in, $params] = sql_in_clause(array_map('intval', $allowed), 'lid');
            $stmt = $db->prepare('SELECT id, name FROM lembaga WHERE id IN (' . $in . ') ORDER BY name ASC');
            $stmt->execute($params);
            $lembagaOptions = $stmt->fetchAll();
        }
        $users = [];
        if (!empty($allowed)) {
            [$in, $params] = sql_in_clause(array_map('intval', $allowed), 'lid');
            $stmt = $db->prepare('SELECT u.id, u.name, u.email, ul.lembaga_id FROM users u JOIN user_lembaga ul ON ul.user_id=u.id WHERE ul.lembaga_id IN (' . $in . ') GROUP BY u.id ORDER BY u.name ASC');
            $stmt->execute($params);
            $users = $stmt->fetchAll();
        }
        $this->setPageTitle('Keuangan - Invoice Gaji');
        $this->render('Keuangan', 'invoice/gaji', [
            'title' => 'Invoice Gaji',
            'lembagaOptions' => $lembagaOptions,
            'users' => $users,
        ]);
    }

    private function renderTemplate(string $tpl, array $ctx): string
    {
        $out = $tpl;
        foreach ($ctx as $k => $v) {
            $out = str_replace('{{' . $k . '}}', (string)$v, $out);
        }
        return $out;
    }

    public function send(): void
    {
        $this->requireAuth();
        $allowed = Access::getUserKeuanganLembagaIds((int)($_SESSION['user_id'] ?? 0));
        if (empty($allowed)) { http_response_code(403); exit('Akses ditolak'); }
        if (!verify_csrf()) { flash('error','Sesi kadaluarsa'); $this->redirect('keuangan/invoice-gaji'); }
        $emailsRaw = trim($_POST['emails'] ?? '');
        $subjectTpl = trim($_POST['subject'] ?? 'Invoice Gaji');
        $bodyTpl = trim($_POST['body'] ?? '');
        $attachPdf = isset($_POST['attach_pdf']);
        $lembagaFilter = (int) ($_POST['lembaga_id'] ?? 0);
        $selectedUserIds = array_filter(array_map('intval', $_POST['user_ids'] ?? []));

        $pickedEmails = [];
        if ($lembagaFilter && !empty($selectedUserIds)) {
            $db = Database::connection();
            [$inUsers, $params] = sql_in_clause($selectedUserIds, 'uid');
            $stmt = $db->prepare('SELECT email, name FROM users WHERE id IN (' . $inUsers . ')');
            $stmt->execute($params);
            foreach ($stmt->fetchAll() as $u) { $pickedEmails[$u['email']] = $u['name']; }
        }

        $emails = array_filter(array_map('trim', preg_split('/[\s,;]+/', $emailsRaw)));
        foreach ($emails as $e) { if ($e !== '') { $pickedEmails[$e] = null; } }

        if (empty($pickedEmails) || $bodyTpl === '') { flash('error','Penerima dan isi email wajib diisi'); $this->redirect('keuangan/invoice-gaji'); }

        $host = Settings::get('smtp_host', getenv('SMTP_HOST') ?: '');
        $port = (int) (Settings::get('smtp_port', getenv('SMTP_PORT') ?: '587') ?: 587);
        $user = Settings::get('smtp_user', getenv('SMTP_USER') ?: '');
        $pass = Settings::get('smtp_pass', getenv('SMTP_PASS') ?: '');
        $secure = Settings::get('smtp_secure', getenv('SMTP_SECURE') ?: 'tls');
        $from = Settings::get('smtp_from', getenv('SMTP_FROM') ?: $user);
        $fromName = Settings::get('smtp_from_name', getenv('SMTP_FROM_NAME') ?: 'Keuangan');
        if (!$host || !$user) { flash('error','Konfigurasi SMTP belum lengkap'); $this->redirect('keuangan/invoice-gaji'); }

        $success = 0; $fail = 0; $errors = [];
        $db = Database::connection();
        $recipientsList = implode(',', array_keys($pickedEmails));
        $logStmt = $db->prepare('INSERT INTO email_log(module, lembaga_id, subject_template, body_template, success_count, fail_count, error_summary, recipients, created_by) VALUES(?,?,?,?,?,?,?,?,?)');
        $logStmt->execute(['keuangan_invoice_gaji', $lembagaFilter ?: null, $subjectTpl, $bodyTpl, 0, 0, null, $recipientsList, (int)$_SESSION['user_id']]);
        $logId = (int) $db->lastInsertId();
        $insRec = $db->prepare('INSERT INTO email_log_recipient(log_id, email, status, error) VALUES (?,?,?,?)');

        foreach ($pickedEmails as $email => $name) {
            $ctx = [
                'nama' => $name ?: $email,
                'bulan' => date('F Y'),
            ];
            $subject = $this->renderTemplate($subjectTpl, $ctx);
            $body = $this->renderTemplate($bodyTpl, $ctx);
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
                $mail->addAddress($email, (string)$ctx['nama']);
                $mail->Subject = $subject;
                $mail->isHTML(true);
                $mail->Body = $body;
                $mail->AltBody = strip_tags(str_replace(['<br>','<br/>','<br />'], "\n", $body));
                if ($attachPdf) {
                    $dompdf = new Dompdf();
                    $html = '<html><head><meta charset="utf-8"></head><body>' . $body . '</body></html>';
                    $dompdf->loadHtml($html, 'UTF-8');
                    $dompdf->setPaper('A4', 'portrait');
                    $dompdf->render();
                    $mail->addStringAttachment($dompdf->output(), 'invoice-gaji-' . date('Ym') . '.pdf', 'base64', 'application/pdf');
                }
                $mail->send();
                $success++;
                $insRec->execute([$logId, $email, 'sent', null]);
            } catch (MailException $e) {
                $fail++;
                $errors[] = $email . ': ' . $e->getMessage();
                $insRec->execute([$logId, $email, 'failed', $e->getMessage()]);
            }
        }

        $errSum = $fail ? implode(' | ', $errors) : null;
        $db->prepare('UPDATE email_log SET success_count=?, fail_count=?, error_summary=? WHERE id=?')->execute([$success, $fail, $errSum, $logId]);

        if ($fail === 0) { flash('success', 'Invoice terkirim ke ' . $success . ' penerima'); }
        else { flash('warning', 'Berhasil: ' . $success . ', Gagal: ' . $fail . '. ' . $errSum); }
        $this->redirect('keuangan/invoice-gaji');
    }
}