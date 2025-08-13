<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0"><?= e($title) ?></h6>
  <div class="d-flex gap-2">
    <a href="<?= base_url('sims/surat-create?type=keluar') ?>" class="btn btn-primary">Tambah Surat</a>
    <a href="<?= base_url('sims/export-csv?tipe=keluar&' . http_build_query($filters)) ?>" class="btn btn-outline-secondary">Export CSV</a>
    <a href="<?= base_url('sims/export-pdf?tipe=keluar&' . http_build_query($filters)) ?>" class="btn btn-outline-secondary">Export PDF</a>
  </div>
</div>
<div class="card shadow-none border mb-3"><div class="card-body">
  <form method="get" class="row g-2">
    <div class="col-md-2"><input type="number" name="lembaga_id" class="form-control" placeholder="Lembaga ID" value="<?= e((string)($filters['lembaga_id'] ?? '')) ?>"></div>
    <div class="col-md-2"><input type="number" name="tahun" class="form-control" placeholder="Tahun" value="<?= e((string)($filters['tahun'] ?? '')) ?>"></div>
    <div class="col-md-2"><input type="date" name="tgl_from" class="form-control" value="<?= e((string)($filters['tgl_from'] ?? '')) ?>"></div>
    <div class="col-md-2"><input type="date" name="tgl_to" class="form-control" value="<?= e((string)($filters['tgl_to'] ?? '')) ?>"></div>
    <div class="col-md-2"><input type="text" name="klasifikasi" class="form-control" placeholder="Klasifikasi" value="<?= e((string)($filters['klasifikasi'] ?? '')) ?>"></div>
    <div class="col-md-2"><button class="btn btn-outline-primary w-100">Filter</button></div>
  </form>
</div></div>
<div class="card shadow-none border">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table mb-0 datatable">
        <thead>
          <tr><th>Tanggal</th><th>Nomor</th><th>Perihal</th><th>Klasifikasi</th><th>Pengirim</th><th>Penerima</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          <?php foreach ($surat as $s): ?>
          <tr>
            <td><?= e($s['tanggal']) ?></td>
            <td><?= e($s['nomor_surat']) ?></td>
            <td><?= e($s['perihal']) ?></td>
            <td><?= e((string)$s['klasifikasi_kode']) ?></td>
            <td><?= e((string)$s['pengirim']) ?></td>
            <td><?= e((string)$s['penerima']) ?></td>
            <td class="d-flex gap-2">
              <a class="btn btn-sm btn-outline-primary" href="<?= base_url('sims/surat-edit?id=' . (int)$s['id']) ?>">Edit</a>
              <form action="<?= base_url('sims/surat-delete') ?>" method="post" onsubmit="return confirm('Hapus surat?')">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>