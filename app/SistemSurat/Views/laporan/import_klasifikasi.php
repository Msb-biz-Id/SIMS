<div class="page-header d-print-none">
  <div class="row g-2 align-items-center">
    <div class="col"><h2 class="page-title">Import Klasifikasi Surat (CSV)</h2></div>
    <div class="col-auto ms-auto">
      <div class="btn-list">
        <a href="<?= base_url('sims/laporan-agenda') ?>" class="btn">Kembali</a>
      </div>
    </div>
  </div>
</div>
<div class="card"><div class="card-body">
  <form action="<?= base_url('sims/do-import-klasifikasi') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="mb-3">
      <label class="form-label">File CSV (kolom: kode, nama)</label>
      <input type="file" name="file" class="form-control" accept=".csv" required>
    </div>
    <button type="submit" class="btn btn-primary">Import</button>
  </form>
</div></div>