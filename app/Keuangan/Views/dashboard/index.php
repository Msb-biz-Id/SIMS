<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0"><?= e($title) ?></h6>
</div>
<div class="card shadow-none border mb-3">
  <div class="card-body p-20">
    <form method="get" class="row g-2 align-items-end">
      <div class="col-md-6">
        <label class="form-label">Lembaga</label>
        <select name="lembaga_id" class="form-select">
          <?php foreach ($lembagaAkses as $lid): ?>
            <option value="<?= (int)$lid ?>" <?= (isset($_GET['lembaga_id']) && (int)$_GET['lembaga_id']===(int)$lid) ? 'selected' : '' ?>>Lembaga #<?= (int)$lid ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <button class="btn btn-outline-primary w-100">Terapkan</button>
      </div>
    </form>
  </div>
</div>
<div class="card shadow-none border">
  <div class="card-body p-20">
    <p class="mb-0 text-secondary-light">Halaman placeholder untuk transaksi keuangan keluar-masuk.</p>
  </div>
</div>