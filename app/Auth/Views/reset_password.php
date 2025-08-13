<section class="auth bg-base d-flex flex-wrap">  
  <div class="auth-left d-lg-block d-none">
    <div class="d-flex align-items-center flex-column h-100 justify-content-center">
      <img src="assets/images/auth/auth-img.png" alt="">
    </div>
  </div>
  <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
    <div class="max-w-464-px mx-auto w-100">
      <div>
        <a href="<?= base_url() ?>" class="mb-40 max-w-290-px">
          <img src="assets/images/logo.png" alt="">
        </a>
        <h4 class="mb-12">Reset Password</h4>
        <p class="mb-24 text-secondary-light text-lg">Masukkan password baru Anda</p>
        <?php if (!empty($_SESSION['error'])): ?>
          <div class="alert alert-danger" role="alert"><?= e($_SESSION['error']) ?></div>
          <?php $_SESSION['error'] = null; endif; ?>
      </div>
      <form action="<?= base_url('auth/do-reset-password') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="token" value="<?= e($token) ?>">
        <div class="position-relative mb-16">
          <div class="icon-field">
            <span class="icon top-50 translate-middle-y">
              <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
            </span>
            <input type="password" name="password" class="form-control h-56-px bg-neutral-50 radius-12" id="your-password" placeholder="Password baru" required>
          </div>
        </div>
        <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-24"> Ubah Password</button>
      </form>
    </div>
  </div>
</section>