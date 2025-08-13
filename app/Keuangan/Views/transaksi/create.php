<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Tambah Transaksi</h6>
  <a href="<?= base_url('keuangan/transaksi') ?>" class="btn btn-secondary">Kembali</a>
</div>
<div class="card shadow-none border"><div class="card-body">
  <form action="<?= base_url('keuangan/transaksi/store') ?>" method="post">
    <?= csrf_field() ?>
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Lembaga</label>
        <select name="lembaga_id" class="form-select" required>
          <?php foreach ($lembagaAkses as $lid): ?>
            <option value="<?= (int)$lid ?>">Lembaga #<?= (int)$lid ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" class="form-control" value="<?= e(date('Y-m-d')) ?>" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Jenis</label>
        <select name="jenis" class="form-select">
          <option value="masuk">Masuk</option>
          <option value="keluar">Keluar</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Kategori</label>
        <input type="text" name="kategori" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">Nominal</label>
        <input type="number" step="0.01" name="nominal" class="form-control" required>
      </div>
      <div class="col-md-12">
        <label class="form-label">Keterangan</label>
        <textarea name="keterangan" class="form-control" rows="3"></textarea>
      </div>
    </div>
    <div class="mt-3"><button class="btn btn-primary">Simpan</button></div>
  </form>
</div></div>