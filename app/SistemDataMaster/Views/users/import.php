<div class="page-header d-print-none">
  <div class="row g-2 align-items-center">
    <div class="col"><h2 class="page-title">Import Users</h2></div>
    <div class="col-auto ms-auto">
      <div class="btn-list">
        <a href="<?= base_url('users') ?>" class="btn">Kembali</a>
        <a href="<?= base_url('users/export') ?>" class="btn">Export Users</a>
      </div>
    </div>
  </div>
</div>
<div class="card"><div class="card-body">
  <form action="<?= base_url('users/do-import') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="mb-3">
      <label class="form-label">File Excel/CSV (kolom: name, email, password, roles, ...)</label>
      <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
    </div>
    <button type="submit" class="btn btn-primary">Import</button>
  </form>
</div></div>