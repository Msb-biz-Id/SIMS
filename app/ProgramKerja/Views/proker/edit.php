<div class="page-header d-print-none">
	<div class="row g-2 align-items-center">
		<div class="col"><h2 class="page-title">Edit Program Kerja</h2></div>
		<div class="col-auto ms-auto"><a href="<?= base_url('program-kerja/proker') ?>" class="btn">Kembali</a></div>
	</div>
</div>
<div class="card"><div class="card-body">
  <form action="<?= base_url('program-kerja/proker/update') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Lembaga</label>
        <input type="text" class="form-control" value="<?= e($row['lembaga_name'] ?? ('#'.(int)$row['lembaga_id'])) ?>" disabled>
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