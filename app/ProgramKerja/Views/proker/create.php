<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Tambah Program Kerja</h6>
  <a href="<?= base_url('program-kerja/proker') ?>" class="btn btn-secondary">Kembali</a>
</div>
<div class="card shadow-none border"><div class="card-body">
  <form action="<?= base_url('program-kerja/proker/store') ?>" method="post">
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
        <label class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Periode (Tahun)</label>
        <input type="number" name="periode_year" class="form-control" value="<?= e(date('Y')) ?>" required>
      </div>
      <div class="col-md-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
      </div>
    </div>
    <div class="mt-3"><button class="btn btn-primary">Simpan</button></div>
  </form>
</div></div>