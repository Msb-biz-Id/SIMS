<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Import Klasifikasi Surat (CSV)</h6>
  <a href="<?= base_url('sims/laporan-agenda') ?>" class="btn btn-secondary">Kembali</a>
</div>
<div class="card shadow-none border"><div class="card-body">
  <form action="<?= base_url('sims/do-import-klasifikasi') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="mb-3">
      <label class="form-label">File CSV (kolom: kode, nama)</label>
      <input type="file" name="file" class="form-control" accept=".csv" required>
    </div>
    <button type="submit" class="btn btn-primary">Import</button>
  </form>
</div></div>