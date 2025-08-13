<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Edit Program Kerja</h6>
  <a href="<?= base_url('program-kerja/proker') ?>" class="btn btn-secondary">Kembali</a>
</div>
<div class="card shadow-none border"><div class="card-body">
  <form action="<?= base_url('program-kerja/proker/update') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Lembaga</label>
        <input type="text" class="form-control" value="#<?= (int)$row['lembaga_id'] ?>" disabled>
      </div>
      <div class="col-md-4">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control" value="<?= e($row['nama']) ?>" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Periode (Tahun)</label>
        <input type="number" name="periode_year" class="form-control" value="<?= e((string)$row['periode_year']) ?>" required>
      </div>
      <div class="col-md-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3"><?= e((string)$row['deskripsi']) ?></textarea>
      </div>
    </div>
    <div class="mt-3"><button class="btn btn-primary">Simpan</button></div>
  </form>
</div></div>