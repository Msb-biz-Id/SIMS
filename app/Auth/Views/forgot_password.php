<section class="auth forgot-password-page bg-base d-flex flex-wrap">  
  <div class="auth-left d-lg-block d-none">
    <div class="d-flex align-items-center flex-column h-100 justify-content-center">
      <img src="assets/images/auth/forgot-pass-img.png" alt="">
    </div>
  </div>
  <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
    <div class="max-w-464-px mx-auto w-100">
      <div>
        <h4 class="mb-12">Lupa Password</h4>
        <p class="mb-16 text-secondary-light text-lg">Masukkan email terdaftar untuk menerima tautan reset password.</p>
        <?php if (!empty($_SESSION['error'])): ?>
          <div class="alert alert-danger" role="alert"><?= e($_SESSION['error']) ?></div>
          <?php $_SESSION['error'] = null; endif; ?>
        <?php if (!empty($_SESSION['success'])): ?>
          <div class="alert alert-success" role="alert"><?= e($_SESSION['success']) ?></div>
          <?php $_SESSION['success'] = null; endif; ?>
      </div>
      <form action="<?= base_url('auth/do-forgot-password') ?>" method="post">
        <?= csrf_field() ?>
        <div class="icon-field">
          <span class="icon top-50 translate-middle-y">
            <iconify-icon icon="mage:email"></iconify-icon>
          </span>
          <input type="email" name="email" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Email" required>
        </div>
        <div class="mt-16">
          <div class="cf-turnstile" data-sitekey="<?= e($turnstile_site_key) ?>"></div>
        </div>
        <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-24">Kirim</button>
        <div class="text-center">
          <a href="<?= base_url('auth/login') ?>" class="text-primary-600 fw-bold mt-24">Kembali ke Masuk</a>
        </div>
      </form>
    </div>
  </div>
</section>