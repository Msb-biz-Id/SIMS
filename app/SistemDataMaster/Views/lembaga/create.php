<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Tambah Lembaga</h6>
  <a href="<?= base_url('lembaga') ?>" class="btn btn-secondary">Kembali</a>
</div>
<div class="card shadow-none border">
  <div class="card-body">
    <form action="<?= base_url('lembaga/store') ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nama</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Induk (opsional)</label>
          <input type="number" name="parent_id" class="form-control" placeholder="ID lembaga induk">
        </div>
        <div class="col-md-12">
          <label class="form-label">Deskripsi</label>
          <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">Logo</label>
          <input type="file" name="logo" class="form-control" accept="image/*">
        </div>
        <div class="col-md-6 d-flex align-items-end">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_keuangan" id="is_keuangan">
            <label class="form-check-label" for="is_keuangan"> Tandai sebagai lembaga keuangan</label>
          </div>
        </div>
      </div>
      <div class="mt-3">
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>