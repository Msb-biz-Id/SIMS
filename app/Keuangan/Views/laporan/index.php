<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0"><?= e($title) ?></h6>
</div>
<div class="card shadow-none border mb-3"><div class="card-body">
  <form method="get" class="row g-2 align-items-end">
    <div class="col-md-4">
      <label class="form-label">Lembaga</label>
      <select name="lembaga_id" class="form-select">
        <option value="">- Semua -</option>
        <?php foreach ($lembagaOptions as $opt): ?>
          <option value="<?= (int)$opt['id'] ?>" <?= ((int)($filters['lembaga_id'] ?? 0)===(int)$opt['id']) ? 'selected' : '' ?>><?= e($opt['name']) ?> (#<?= (int)$opt['id'] ?>)</option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2">
      <label class="form-label">Dari</label>
      <input type="date" name="tgl_from" class="form-control" value="<?= e((string)($filters['tgl_from'] ?? '')) ?>">
    </div>
    <div class="col-md-2">
      <label class="form-label">Sampai</label>
      <input type="date" name="tgl_to" class="form-control" value="<?= e((string)($filters['tgl_to'] ?? '')) ?>">
    </div>
    <div class="col-md-2">
      <button class="btn btn-outline-primary w-100">Terapkan</button>
    </div>
  </form>
</div></div>
<div class="row g-3 mb-3">
  <div class="col-md-4">
    <div class="card shadow-none border h-100"><div class="card-body">
      <div class="text-secondary">Saldo</div>
      <div class="h4 mb-0">Rp <?= number_format((float)$saldo,2,',','.') ?></div>
    </div></div>
  </div>
  <div class="col-md-4">
    <div class="card shadow-none border h-100"><div class="card-body">
      <div class="text-secondary">Pendapatan</div>
      <div class="h4 mb-0">Rp <?= number_format((float)$lr['pendapatan'],2,',','.') ?></div>
    </div></div>
  </div>
  <div class="col-md-4">
    <div class="card shadow-none border h-100"><div class="card-body">
      <div class="text-secondary">Pengeluaran</div>
      <div class="h4 mb-0">Rp <?= number_format((float)$lr['pengeluaran'],2,',','.') ?></div>
    </div></div>
  </div>
</div>
<div class="card shadow-none border mb-3"><div class="card-body">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <div class="h6 mb-0">Laba/Rugi</div>
    <?php $lrn = (float)$lr['laba_rugi']; $lrClass = $lrn>=0 ? 'text-success' : 'text-danger'; ?>
    <div class="fw-semibold <?= $lrClass ?>">Rp <?= number_format($lrn,2,',','.') ?></div>
  </div>
</div></div>
<div class="card shadow-none border"><div class="card-body p-0">
  <div class="table-responsive">
    <table class="table mb-0 datatable">
      <thead><tr><th>Tanggal</th><th>Jenis</th><th>Nominal</th><th>Saldo Kumulatif</th></tr></thead>
      <tbody>
        <?php foreach ($arusKas as $r): ?>
        <tr>
          <td><?= e($r['tanggal']) ?></td>
          <td><?= e($r['jenis']) ?></td>
          <td><?= number_format((float)$r['nominal'],2,',','.') ?></td>
          <td><?= number_format((float)$r['saldo'],2,',','.') ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div></div>