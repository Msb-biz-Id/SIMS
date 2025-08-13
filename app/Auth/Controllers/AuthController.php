<?php
namespace App\Auth\Controllers;

use App\Core\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

final class AuthController extends Controller
{
    public function login(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('/');
        }
        $this->setPageTitle('Masuk');
        $this->renderAuth('login', [
            'turnstile_site_key' => getenv('TURNSTILE_SITE_KEY') ?: '',
        ]);
    }

    public function doLogin(): void
    {
        if (!$this->verifyTurnstile()) {
            $_SESSION['error'] = 'Verifikasi gagal. Coba lagi.';
            $this->redirect('auth/login');
        }
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        if ($email === '' || $password === '') {
            $_SESSION['error'] = 'Email dan password wajib diisi';
            $this->redirect('auth/login');
        }
        // TODO: Ganti dengan validasi user dari DB
        if ($email === 'admin@example.com' && $password === 'admin') {
            $_SESSION['user_id'] = 1;
            $_SESSION['user_email'] = $email;
            $this->redirect('/');
        }
        $_SESSION['error'] = 'Kredensial tidak valid';
        $this->redirect('auth/login');
    }

    public function forgotPassword(): void
    {
        $this->setPageTitle('Lupa Password');
        $this->renderAuth('forgot_password', [
            'turnstile_site_key' => getenv('TURNSTILE_SITE_KEY') ?: '',
        ]);
    }

    public function doForgotPassword(): void
    {
        if (!$this->verifyTurnstile()) {
            $_SESSION['error'] = 'Verifikasi gagal. Coba lagi.';
            $this->redirect('auth/forgot-password');
        }
        $email = trim($_POST['email'] ?? '');
        if ($email === '') {
            $_SESSION['error'] = 'Email wajib diisi';
            $this->redirect('auth/forgot-password');
        }
        // TODO: generate token reset password dan simpan ke DB
        $token = bin2hex(random_bytes(16));
        $resetLink = base_url('auth/reset-password?token=' . urlencode($token));

        try {
            $this->sendMail($email, 'Reset Password', 'Klik tautan berikut untuk reset password: ' . $resetLink);
            $_SESSION['success'] = 'Silakan cek email Anda untuk instruksi reset password';
        } catch (\Throwable $e) {
            $_SESSION['error'] = 'Gagal mengirim email: ' . $e->getMessage();
        }
        $this->redirect('auth/forgot-password');
    }

    public function logout(): void
    {
        session_destroy();
        $this->redirect('auth/login');
    }

    private function sendMail(string $toEmail, string $subject, string $body): void
    {
        if (!class_exists(\PHPMailer\PHPMailer\PHPMailer::class)) {
            throw new \RuntimeException('PHPMailer tidak terpasang. Jalankan composer install.');
        }
        $host = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
        $port = (int) (getenv('SMTP_PORT') ?: 587);
        $user = getenv('SMTP_USER') ?: '';
        $pass = getenv('SMTP_PASS') ?: '';
        $from = getenv('MAIL_FROM') ?: ($user ?: 'no-reply@example.com');
        $fromName = getenv('MAIL_FROM_NAME') ?: 'Platform MST';

        $mailer = new PHPMailer(true);
        $mailer->isSMTP();
        $mailer->Host = $host;
        $mailer->SMTPAuth = true;
        $mailer->Username = $user;
        $mailer->Password = $pass;
        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailer->Port = $port;
        $mailer->setFrom($from, $fromName);
        $mailer->addAddress($toEmail);
        $mailer->isHTML(false);
        $mailer->Subject = $subject;
        $mailer->Body = $body;
        $mailer->send();
    }

    private function verifyTurnstile(): bool
    {
        $secret = getenv('TURNSTILE_SECRET_KEY') ?: '';
        $response = $_POST['cf-turnstile-response'] ?? '';
        if ($secret === '' || $response === '') {
            return false;
        }
        $payload = http_build_query([
            'secret' => $secret,
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => $payload,
                'timeout' => 5,
            ],
        ];
        $context  = stream_context_create($options);
        $result = @file_get_contents('https://challenges.cloudflare.com/turnstile/v0/siteverify', false, $context);
        if ($result === false) {
            return false;
        }
        $json = json_decode($result, true);
        return isset($json['success']) && $json['success'] === true;
    }
}