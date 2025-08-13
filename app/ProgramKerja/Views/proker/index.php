<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Program Kerja</h6>
  <a href="<?= base_url('program-kerja/proker/create') ?>" class="btn btn-primary">Tambah</a>
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
      <label class="form-label">Periode</label>
      <input type="number" name="periode_year" class="form-control" value="<?= e((string)($filters['periode_year'] ?? '')) ?>" placeholder="Tahun">
    </div>
    <div class="col-md-2">
      <button class="btn btn-outline-primary w-100">Terapkan</button>
    </div>
  </form>
</div></div>
<div class="card shadow-none border"><div class="card-body p-0">
  <div class="table-responsive">
    <table class="table mb-0 datatable">
      <thead><tr><th>Lembaga</th><th>Nama</th><th>Periode</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
        <tr>
          <td>#<?= (int)$r['lembaga_id'] ?></td>
          <td><?= e($r['nama']) ?></td>
          <td><?= e((string)$r['periode_year']) ?></td>
          <td class="d-flex gap-2">
            <a class="btn btn-sm btn-outline-primary" href="<?= base_url('program-kerja/proker/edit?id=' . (int)$r['id']) ?>">Edit</a>
            <form action="<?= base_url('program-kerja/proker/delete') ?>" method="post" onsubmit="return confirm('Hapus program kerja?')">
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