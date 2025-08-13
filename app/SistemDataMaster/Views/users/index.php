<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Users</h6>
  <div class="d-flex gap-2">
    <a href="<?= base_url('users/import') ?>" class="btn btn-outline-secondary">Import</a>
    <a href="<?= base_url('users/export') ?>" class="btn btn-outline-secondary">Export</a>
    <a href="<?= base_url('users/create') ?>" class="btn btn-primary">Tambah User</a>
  </div>
</div>
<?php require __DIR__ . '/../../ViewsPartials/_table_users.php'; ?>