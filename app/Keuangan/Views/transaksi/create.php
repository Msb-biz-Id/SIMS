<div class="page-header d-print-none">
	<div class="row g-2 align-items-center">
		<div class="col"><h2 class="page-title">Tambah Transaksi</h2></div>
		<div class="col-auto ms-auto"><a href="<?= base_url('keuangan/transaksi') ?>" class="btn">Kembali</a></div>
	</div>
</div>
<div class="card"><div class="card-body">
  <form action="<?= base_url('keuangan/transaksi/store') ?>" method="post">
    <?= csrf_field() ?>
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Lembaga</label>
        <select name="lembaga_id" id="select-lembaga" class="form-select" required>
          <?php foreach ($lembagaOptions as $opt): ?>
            <option value="<?= (int)$opt['id'] ?>"><?= e($opt['name']) ?> (#<?= (int)$opt['id'] ?>)</option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Program Kerja (opsional)</label>
        <select name="proker_id" id="select-proker" class="form-select">
          <option value="">- Tidak terkait -</option>
          <?php foreach ($prokerOptions as $p): ?>
            <option data-lembaga="<?= (int)$p['lembaga_id'] ?>" value="<?= (int)$p['id'] ?>"><?= e($p['nama']) ?></option>
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
<script>
  (function(){
    const selectL = document.getElementById('select-lembaga');
    const selectP = document.getElementById('select-proker');
    function filterProker(){
      const lid = selectL.value;
      for (const opt of selectP.options) {
        if (!opt.value) { opt.hidden = false; continue; }
        const ol = opt.getAttribute('data-lembaga');
        opt.hidden = (ol !== lid);
      }
      if (selectP.selectedOptions[0] && selectP.selectedOptions[0].hidden) { selectP.value = ''; }
    }
    selectL.addEventListener('change', filterProker);
    filterProker();
  })();
</script>