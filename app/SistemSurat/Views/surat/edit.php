<div class="page-header d-print-none">
  <div class="row g-2 align-items-center">
    <div class="col"><h2 class="page-title">Edit Surat <?= e(ucfirst($surat['tipe'])) ?> - <?= e($surat['nomor_surat']) ?></h2></div>
    <div class="col-auto ms-auto">
      <div class="btn-list">
        <a href="<?= base_url($surat['tipe'] === 'masuk' ? 'sims/surat-masuk' : 'sims/surat-keluar') ?>" class="btn">Kembali</a>
      </div>
    </div>
  </div>
</div>
<div class="card"><div class="card-body">
  <form action="<?= base_url('sims/surat-update') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int)$surat['id'] ?>">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" class="form-control" value="<?= e($surat['tanggal']) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Klasifikasi (Kode)</label>
        <input type="text" name="klasifikasi_kode" class="form-control" value="<?= e((string)$surat['klasifikasi_kode']) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Nomor Surat</label>
        <input type="text" class="form-control" value="<?= e($surat['nomor_surat']) ?>" disabled>
      </div>
      <div class="col-md-12">
        <label class="form-label">Perihal</label>
        <input type="text" name="perihal" class="form-control" value="<?= e($surat['perihal']) ?>">
      </div>
      <div class="col-md-12">
        <label class="form-label">Ringkas</label>
        <textarea name="ringkas" class="form-control" rows="3"><?= e((string)$surat['ringkas']) ?></textarea>
      </div>
      <div class="col-md-6">
        <label class="form-label">Pengirim</label>
        <input type="text" name="pengirim" class="form-control" value="<?= e((string)$surat['pengirim']) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Penerima</label>
        <input type="text" name="penerima" class="form-control" value="<?= e((string)$surat['penerima']) ?>">
      </div>
    </div>
    <div class="mt-3"><button type="submit" class="btn btn-primary">Simpan</button></div>
  </form>
</div></div>

<div class="mt-3 card"><div class="card-body">
  <h6 class="mb-3">Lampiran</h6>
  <form action="<?= base_url('sims/lampiran-upload') ?>" method="post" enctype="multipart/form-data" class="mb-3">
    <?= csrf_field() ?>
    <input type="hidden" name="surat_id" value="<?= (int)$surat['id'] ?>">
    <div class="row g-2 align-items-end">
      <div class="col-md-8">
        <label class="form-label">Tambah Lampiran (JPG, PNG, PDF, DOC/DOCX)</label>
        <input type="file" name="file" class="form-control" accept="image/*,.pdf,.doc,.docx" required>
      </div>
      <div class="col-md-4">
        <button type="submit" class="btn btn-outline-primary">Upload</button>
      </div>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table mb-0">
      <thead><tr><th>#</th><th>Nama</th><th>Ukuran</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php foreach ($lampiran as $i => $f): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><a href="<?= base_url($f['file_path']) ?>" target="_blank"><?= e($f['original_name']) ?></a></td>
          <td><?= round($f['size']/1024) ?> KB</td>
          <td>
            <form action="<?= base_url('sims/lampiran-delete') ?>" method="post" onsubmit="return confirm('Hapus lampiran?')">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int)$f['id'] ?>">
              <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div></div>