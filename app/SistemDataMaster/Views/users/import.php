<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Import Users</h6>
  <div class="d-flex gap-2">
    <a href="<?= base_url('users') ?>" class="btn btn-secondary">Kembali</a>
    <a href="<?= base_url('users/export') ?>" class="btn btn-outline-primary">Export Users</a>
  </div>
</div>
<div class="card shadow-none border">
  <div class="card-body">
    <form action="<?= base_url('users/do-import') ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="form-label">File Excel/CSV (kolom: name, email, password, roles, ...)</label>
        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
      </div>
      <button type="submit" class="btn btn-primary">Import</button>
    </form>
  </div>
</div>