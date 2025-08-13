<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Transaksi Keuangan</h6>
  <a href="<?= base_url('keuangan/transaksi/create') ?>" class="btn btn-primary">Tambah</a>
</div>
<div class="card shadow-none border mb-3"><div class="card-body">
  <form method="get" class="row g-2 align-items-end">
    <div class="col-md-4">
      <label class="form-label">Lembaga</label>
      <select name="lembaga_id" class="form-select">
        <option value="">- Semua -</option>
        <?php foreach ($lembagaAkses as $lid): ?>
          <option value="<?= (int)$lid ?>" <?= ((int)($filters['lembaga_id'] ?? 0)===(int)$lid) ? 'selected' : '' ?>>Lembaga #<?= (int)$lid ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2">
      <label class="form-label">Jenis</label>
      <select name="jenis" class="form-select">
        <option value="">- Semua -</option>
        <option value="masuk" <?= ($filters['jenis'] ?? '')==='masuk' ? 'selected' : '' ?>>Masuk</option>
        <option value="keluar" <?= ($filters['jenis'] ?? '')==='keluar' ? 'selected' : '' ?>>Keluar</option>
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
<div class="card shadow-none border"><div class="card-body p-0">
  <div class="table-responsive">
    <table class="table mb-0 datatable">
      <thead><tr><th>Tanggal</th><th>Lembaga</th><th>Jenis</th><th>Kategori</th><th>Nominal</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= e($r['tanggal']) ?></td>
          <td>#<?= (int)$r['lembaga_id'] ?></td>
          <td><?= e($r['jenis']) ?></td>
          <td><?= e((string)$r['kategori']) ?></td>
          <td><?= number_format((float)$r['nominal'],2,',','.') ?></td>
          <td class="d-flex gap-2">
            <a class="btn btn-sm btn-outline-primary" href="<?= base_url('keuangan/transaksi/edit?id=' . (int)$r['id']) ?>">Edit</a>
            <form action="<?= base_url('keuangan/transaksi/delete') ?>" method="post" onsubmit="return confirm('Hapus transaksi?')">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
              <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div></div>