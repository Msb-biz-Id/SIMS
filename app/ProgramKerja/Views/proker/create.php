<div class="page-header d-print-none">
	<div class="row g-2 align-items-center">
		<div class="col"><h2 class="page-title">Tambah Program Kerja</h2></div>
		<div class="col-auto ms-auto"><a href="<?= base_url('program-kerja/proker') ?>" class="btn">Kembali</a></div>
	</div>
</div>
<div class="card"><div class="card-body">
  <form action="<?= base_url('program-kerja/proker/store') ?>" method="post">
    <?= csrf_field() ?>
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Lembaga</label>
        <select name="lembaga_id" class="form-select" required>
          <?php foreach ($lembagaOptions as $opt): ?>
            <option value="<?= (int)$opt['id'] ?>"><?= e($opt['name']) ?> (#<?= (int)$opt['id'] ?>)</option>
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