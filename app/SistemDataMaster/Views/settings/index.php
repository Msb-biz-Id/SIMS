<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Pengaturan Sistem</h6>
</div>
<div class="card shadow-none border"><div class="card-body">
  <form action="<?= base_url('settings/save') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Judul Situs</label>
        <input type="text" name="site_title" class="form-control" value="<?= e($site_title) ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Deskripsi Situs</label>
        <input type="text" name="site_description" class="form-control" value="<?= e($site_description) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Logo (PNG/JPG/SVG)</label>
        <input type="file" name="site_logo" class="form-control" accept="image/png,image/jpeg,image/svg+xml">
        <?php if (!empty($logo_path)): ?>
          <div class="mt-2"><img src="<?= base_url($logo_path) ?>" alt="logo" height="50"></div>
        <?php endif; ?>
      </div>
      <div class="col-md-6">
        <label class="form-label">Favicon (PNG/ICO)</label>
        <input type="file" name="site_favicon" class="form-control" accept="image/png,image/x-icon">
        <?php if (!empty($favicon_path)): ?>
          <div class="mt-2"><img src="<?= base_url($favicon_path) ?>" alt="favicon" height="32"></div>
        <?php endif; ?>
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="turnstile_enabled" id="turnstile_enabled" <?= $turnstile_enabled ? 'checked' : '' ?>>
          <label class="form-check-label" for="turnstile_enabled"> Aktifkan Turnstile</label>
        </div>
      </div>
      <div class="col-md-5">
        <label class="form-label">Turnstile Site Key</label>
        <input type="text" name="turnstile_site_key" class="form-control" value="<?= e($turnstile_site_key) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Turnstile Secret Key</label>
        <input type="text" name="turnstile_secret_key" class="form-control" value="<?= e($turnstile_secret_key) ?>">
      </div>
    </div>

    <hr class="my-4">
    <h6 class="fw-semibold mb-3">SMTP (Email)</h6>
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Host</label>
        <input type="text" name="smtp_host" class="form-control" value="<?= e($smtp_host) ?>">
      </div>
      <div class="col-md-2">
        <label class="form-label">Port</label>
        <input type="number" name="smtp_port" class="form-control" value="<?= e($smtp_port) ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Secure</label>
        <select name="smtp_secure" class="form-select">
          <option value="tls" <?= ($smtp_secure==='tls') ? 'selected' : '' ?>>TLS</option>
          <option value="ssl" <?= ($smtp_secure==='ssl') ? 'selected' : '' ?>>SSL</option>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Username</label>
        <input type="text" name="smtp_user" class="form-control" value="<?= e($smtp_user) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Password</label>
        <input type="password" name="smtp_pass" class="form-control" value="<?= e($smtp_pass) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">From Email</label>
        <input type="email" name="smtp_from" class="form-control" value="<?= e($smtp_from) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">From Name</label>
        <input type="text" name="smtp_from_name" class="form-control" value="<?= e($smtp_from_name) ?>">
      </div>
    </div>

    <div class="mt-3"><button type="submit" class="btn btn-primary">Simpan</button></div>
  </form>
</div></div>