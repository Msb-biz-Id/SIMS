<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Tambah Surat <?= e(ucfirst($type)) ?></h6>
  <a href="<?= base_url($type === 'masuk' ? 'sims/surat-masuk' : 'sims/surat-keluar') ?>" class="btn btn-secondary">Kembali</a>
</div>
<div class="card shadow-none border"><div class="card-body">
  <form action="<?= base_url('sims/surat-store') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="tipe" value="<?= e($type) ?>">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Lembaga ID</label>
        <input type="number" name="lembaga_id" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" class="form-control" value="<?= e(date('Y-m-d')) ?>" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Klasifikasi (Kode)</label>
        <input type="text" name="klasifikasi_kode" class="form-control">
      </div>
      <div class="col-md-12">
        <label class="form-label">Perihal</label>
        <input type="text" name="perihal" class="form-control" required>
      </div>
      <div class="col-md-12">
        <label class="form-label">Ringkas</label>
        <textarea name="ringkas" class="form-control" rows="3"></textarea>
      </div>
      <div class="col-md-6">
        <label class="form-label">Pengirim</label>
        <input type="text" name="pengirim" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">Penerima</label>
        <input type="text" name="penerima" class="form-control">
      </div>
    </div>
    <div class="mt-3"><button type="submit" class="btn btn-primary">Simpan</button></div>
  </form>
</div></div>