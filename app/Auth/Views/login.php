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
        <h4 class="mb-12">Masuk ke Akun</h4>
        <p class="mb-24 text-secondary-light text-lg">Silakan isi kredensial Anda</p>
        <?php if (!empty($_SESSION['error'])): ?>
          <div class="alert alert-danger" role="alert"><?= e($_SESSION['error']) ?></div>
          <?php $_SESSION['error'] = null; endif; ?>
      </div>
      <form action="<?= base_url('auth/do-login') ?>" method="post">
        <?= csrf_field() ?>
        <div class="icon-field mb-16">
          <span class="icon top-50 translate-middle-y">
            <iconify-icon icon="mage:email"></iconify-icon>
          </span>
          <input type="email" name="email" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Email" required>
        </div>
        <div class="position-relative mb-16">
          <div class="icon-field">
            <span class="icon top-50 translate-middle-y">
              <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
            </span>
            <input type="password" name="password" class="form-control h-56-px bg-neutral-50 radius-12" id="your-password" placeholder="Password" required>
          </div>
          <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#your-password"></span>
        </div>

        <div class="mb-16">
          <div class="cf-turnstile" data-sitekey="<?= e($turnstile_site_key) ?>"></div>
        </div>

        <div class="d-flex justify-content-between gap-2">
          <div class="form-check style-check d-flex align-items-center">
            <input class="form-check-input border border-neutral-300" type="checkbox" value="1" id="remember">
            <label class="form-check-label" for="remember">Remember me </label>
          </div>
          <a href="<?= base_url('auth/forgot-password') ?>" class="text-primary-600 fw-medium">Lupa Password?</a>
        </div>

        <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-24"> Masuk</button>
      </form>
    </div>
  </div>
</section>

<script>
function initializePasswordToggle(toggleSelector) {
  $(toggleSelector).on('click', function() {
    $(this).toggleClass("ri-eye-off-line");
    var input = $($(this).attr("data-toggle"));
    if (input.attr("type") === "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });
}
initializePasswordToggle('.toggle-password');
</script>