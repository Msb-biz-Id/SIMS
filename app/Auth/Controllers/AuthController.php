<?php
namespace App\Auth\Controllers;

use App\Core\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use App\SistemDataMaster\Models\User;
use App\Auth\Models\PasswordReset;
use DateTimeImmutable;
use App\Core\Settings;

final class AuthController extends Controller
{
    public function login(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        $this->setPageTitle('Masuk');
        $this->renderAuth('login', [
            'turnstile_site_key' => Settings::get('turnstile_site_key', getenv('TURNSTILE_SITE_KEY') ?: ''),
        ]);
    }

    public function doLogin(): void
    {
        if (!verify_csrf()) {
            $_SESSION['error'] = 'Sesi kedaluwarsa. Muat ulang halaman.';
            $this->redirect('auth/login');
        }
        if (!rate_limit_check('login_' . ($_SERVER['REMOTE_ADDR'] ?? 'x'), 10, 300)) {
            $_SESSION['error'] = 'Terlalu banyak percobaan. Coba lagi beberapa menit.';
            $this->redirect('auth/login');
        }
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
        $userRow = (new User())->findByEmail($email);
        if ($userRow && password_verify($password, $userRow['password_hash'])) {
            $_SESSION['user_id'] = (int) $userRow['id'];
            $_SESSION['user_email'] = $userRow['email'];
            $this->redirect('/dashboard');
        }
        $_SESSION['error'] = 'Kredensial tidak valid';
        $this->redirect('auth/login');
    }

    public function forgotPassword(): void
    {
        $this->setPageTitle('Lupa Password');
        $this->renderAuth('forgot_password', [
            'turnstile_site_key' => Settings::get('turnstile_site_key', getenv('TURNSTILE_SITE_KEY') ?: ''),
        ]);
    }

    public function doForgotPassword(): void
    {
        if (!verify_csrf()) {
            $_SESSION['error'] = 'Sesi kedaluwarsa. Muat ulang halaman.';
            $this->redirect('auth/forgot-password');
        }
        if (!rate_limit_check('forgot_' . ($_SERVER['REMOTE_ADDR'] ?? 'x'), 5, 900)) {
            $_SESSION['error'] = 'Terlalu sering. Coba lagi beberapa saat.';
            $this->redirect('auth/forgot-password');
        }
        if (!$this->verifyTurnstile()) {
            $_SESSION['error'] = 'Verifikasi gagal. Coba lagi.';
            $this->redirect('auth/forgot-password');
        }
        $email = trim($_POST['email'] ?? '');
        if ($email === '') {
            $_SESSION['error'] = 'Email wajib diisi';
            $this->redirect('auth/forgot-password');
        }
        $userRow = (new User())->findByEmail($email);
        if (!$userRow) {
            $_SESSION['error'] = 'Email tidak ditemukan';
            $this->redirect('auth/forgot-password');
        }
        $token = bin2hex(random_bytes(16));
        $expiresAt = (new DateTimeImmutable('+1 hour'));
        (new PasswordReset())->createToken((int) $userRow['id'], $token, $expiresAt);
        $resetLink = base_url('auth/reset-password?token=' . urlencode($token));

        try {
            $this->sendMail($email, 'Reset Password', 'Klik tautan berikut untuk reset password: ' . $resetLink);
            $_SESSION['success'] = 'Silakan cek email Anda untuk instruksi reset password';
        } catch (\Throwable $e) {
            $_SESSION['error'] = 'Gagal mengirim email: ' . $e->getMessage();
        }
        $this->redirect('auth/forgot-password');
    }

    public function resetPassword(): void
    {
        $this->setPageTitle('Reset Password');
        $token = trim($_GET['token'] ?? '');
        $this->renderAuth('reset_password', ['token' => $token]);
    }

    public function doResetPassword(): void
    {
        if (!verify_csrf()) {
            $_SESSION['error'] = 'Sesi kedaluwarsa.';
            $this->redirect('auth/login');
        }
        $token = trim($_POST['token'] ?? '');
        $password = trim($_POST['password'] ?? '');
        if ($token === '' || $password === '') {
            $_SESSION['error'] = 'Token atau password tidak valid';
            $this->redirect('auth/login');
        }
        $pr = new PasswordReset();
        $row = $pr->findValid($token);
        if (!$row) {
            $_SESSION['error'] = 'Token reset tidak valid / kedaluwarsa';
            $this->redirect('auth/login');
        }
        $userId = (int) $row['user_id'];
        (new User())->updateUser($userId, ...$this->getUserNameEmail($userId), password_hash($password, PASSWORD_DEFAULT), null);
        $pr->markUsed((int) $row['id']);
        $_SESSION['success'] = 'Password telah diperbarui. Silakan login.';
        $this->redirect('auth/login');
    }

    private function getUserNameEmail(int $userId): array
    {
        $user = (new User())->findById($userId);
        return [$user['name'], $user['email']];
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
        $host = Settings::get('smtp_host', getenv('SMTP_HOST') ?: '');
        $port = (int) (Settings::get('smtp_port', getenv('SMTP_PORT') ?: '587') ?: 587);
        $user = Settings::get('smtp_user', getenv('SMTP_USER') ?: '');
        $pass = Settings::get('smtp_pass', getenv('SMTP_PASS') ?: '');
        $secure = Settings::get('smtp_secure', getenv('SMTP_SECURE') ?: 'tls');
        $from = Settings::get('smtp_from', getenv('SMTP_FROM') ?: ($user ?: 'no-reply@example.com'));
        $fromName = Settings::get('smtp_from_name', getenv('SMTP_FROM_NAME') ?: 'Platform MST');

        $mailer = new PHPMailer(true);
        $mailer->isSMTP();
        $mailer->Host = $host;
        $mailer->SMTPAuth = true;
        $mailer->Username = $user;
        $mailer->Password = $pass;
        $mailer->SMTPSecure = $secure === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
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
        if (Settings::get('turnstile_enabled', '0') !== '1') {
            return true;
        }
        $secret = Settings::get('turnstile_secret_key', getenv('TURNSTILE_SECRET_KEY') ?: '');
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