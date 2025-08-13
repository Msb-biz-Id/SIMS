<div class="page-header d-print-none">
	<div class="row g-2 align-items-center">
		<div class="col"><h2 class="page-title"><?= e($title) ?></h2></div>
	</div>
</div>
<div class="card mb-3">
  <div class="card-body">
    <form method="get" class="row g-2 align-items-end">
      <div class="col-md-6">
        <label class="form-label">Lembaga</label>
        <select name="lembaga_id" class="form-select">
          <?php if (!empty($lembagaOptions ?? [])): ?>
            <?php foreach ($lembagaOptions as $opt): ?>
              <option value="<?= (int)$opt['id'] ?>" <?= (isset($_GET['lembaga_id']) && (int)$_GET['lembaga_id']===(int)$opt['id']) ? 'selected' : '' ?>><?= e($opt['name']) ?> (#<?= (int)$opt['id'] ?>)</option>
            <?php endforeach; ?>
          <?php else: ?>
            <?php foreach ($lembagaAkses as $lid): ?>
              <option value="<?= (int)$lid ?>" <?= (isset($_GET['lembaga_id']) && (int)$_GET['lembaga_id']===(int)$lid) ? 'selected' : '' ?>>Lembaga #<?= (int)$lid ?></option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
      </div>
      <div class="col-md-2">
        <button class="btn btn-outline-primary w-100">Terapkan</button>
      </div>
    </form>
  </div>
</div>
<div class="card">
  <div class="card-body">
    <p class="mb-0 text-secondary">Halaman placeholder untuk transaksi keuangan keluar-masuk.</p>
  </div>
</div>