<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Tambah User</h6>
  <a href="<?= base_url('users') ?>" class="btn btn-secondary">Kembali</a>
</div>
<div class="card shadow-none border">
  <div class="card-body">
    <form action="<?= base_url('users/store') ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nama</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Avatar</label>
          <input type="file" name="avatar" class="form-control" accept="image/*">
        </div>
        <div class="col-12">
          <label class="form-label">Roles</label>
          <div class="d-flex flex-wrap gap-3">
            <?php foreach ($roles as $role): ?>
              <label class="form-check d-flex align-items-center gap-2">
                <input class="form-check-input" type="checkbox" name="roles[]" value="<?= (int)$role['id'] ?>">
                <span><?= e($role['name']) ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="mt-3">
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>