<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Edit Profil</h6>
  <a href="<?= base_url('profile') ?>" class="btn btn-secondary">Kembali</a>
</div>
<div class="card shadow-none border">
  <div class="card-body">
    <form action="<?= base_url('profile/update') ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nama</label>
          <input type="text" name="name" class="form-control" required value="<?= e($user['name']) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required value="<?= e($user['email']) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Password Baru (opsional)</label>
          <input type="password" name="password" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Avatar</label>
          <input type="file" name="avatar" class="form-control" accept="image/*">
          <?php if (!empty($user['avatar_path'])): ?>
            <div class="mt-2"><img src="<?= base_url($user['avatar_path']) ?>" alt="avatar" height="60"></div>
          <?php endif; ?>
        </div>
      </div>
      <div class="mt-3">
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>