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
      <div class="col-md-6">
        <label class="form-label">Turnstile Site Key</label>
        <input type="text" name="turnstile_site_key" class="form-control" value="<?= e($turnstile_site_key) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Turnstile Secret Key</label>
        <input type="text" name="turnstile_secret_key" class="form-control" value="<?= e($turnstile_secret_key) ?>">
      </div>
    </div>
    <div class="mt-3"><button type="submit" class="btn btn-primary">Simpan</button></div>
  </form>
</div></div>