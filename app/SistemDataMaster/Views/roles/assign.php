<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Assign Roles: <?= e($user['name']) ?></h6>
  <a href="<?= base_url('roles') ?>" class="btn btn-secondary">Kembali</a>
</div>
<div class="card shadow-none border">
  <div class="card-body">
    <form action="<?= base_url('roles/save-assignment') ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="user_id" value="<?= (int)$user['id'] ?>">
      <div class="d-flex flex-wrap gap-3">
        <?php foreach ($roles as $role): $checked = in_array($role['name'], $userRoleNames, true); ?>
          <label class="form-check d-flex align-items-center gap-2">
            <input class="form-check-input" type="checkbox" name="roles[]" value="<?= (int)$role['id'] ?>" <?= $checked ? 'checked' : '' ?>>
            <span><?= e($role['name']) ?></span>
          </label>
        <?php endforeach; ?>
      </div>
      <div class="mt-3">
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>