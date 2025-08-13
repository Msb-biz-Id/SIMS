<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Edit Transaksi</h6>
  <a href="<?= base_url('keuangan/transaksi') ?>" class="btn btn-secondary">Kembali</a>
</div>
<div class="card shadow-none border"><div class="card-body">
  <form action="<?= base_url('keuangan/transaksi/update') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Lembaga</label>
        <input type="text" class="form-control" value="<?= e($row['lembaga_name'] ?? ('#'.(int)$row['lembaga_id'])) ?>" disabled>
      </div>
      <div class="col-md-4">
        <label class="form-label">Program Kerja (opsional)</label>
        <select name="proker_id" class="form-select" disabled title="Ganti relasi proker via hapus dan buat ulang transaksi untuk menjaga integritas">
          <option value="">- Tidak terkait -</option>
          <?php foreach ($prokerOptions as $p): ?>
            <option value="<?= (int)$p['id'] ?>" <?= ((int)($row['proker_id'] ?? 0)===(int)$p['id']) ? 'selected' : '' ?>><?= e($p['nama']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" class="form-control" value="<?= e($row['tanggal']) ?>" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Jenis</label>
        <select name="jenis" class="form-select">
          <option value="masuk" <?= $row['jenis']==='masuk' ? 'selected' : '' ?>>Masuk</option>
          <option value="keluar" <?= $row['jenis']==='keluar' ? 'selected' : '' ?>>Keluar</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Kategori</label>
        <input type="text" name="kategori" class="form-control" value="<?= e((string)$row['kategori']) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Nominal</label>
        <input type="number" step="0.01" name="nominal" class="form-control" value="<?= e((string)$row['nominal']) ?>" required>
      </div>
      <div class="col-md-12">
        <label class="form-label">Keterangan</label>
        <textarea name="keterangan" class="form-control" rows="3"><?= e((string)$row['keterangan']) ?></textarea>
      </div>
    </div>
    <div class="mt-3"><button class="btn btn-primary">Simpan</button></div>
  </form>
</div></div>