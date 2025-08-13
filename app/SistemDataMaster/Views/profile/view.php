<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Profil Saya</h6>
  <a href="<?= base_url('profile/edit') ?>" class="btn btn-primary">Edit Profil</a>
</div>
<div class="card shadow-none border">
  <div class="card-body">
    <div class="d-flex align-items-center gap-3">
      <img src="<?= !empty($user['avatar_path']) ? base_url($user['avatar_path']) : asset_url('images/user.png') ?>" alt="avatar" class="rounded-circle" width="80" height="80">
      <div>
        <h6 class="mb-2"><?= e($user['name']) ?></h6>
        <div class="text-secondary-light"><?= e($user['email']) ?></div>
      </div>
    </div>
  </div>
</div>