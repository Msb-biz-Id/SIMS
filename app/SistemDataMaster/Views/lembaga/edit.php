<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Edit Lembaga</h6>
  <a href="<?= base_url('lembaga') ?>" class="btn btn-secondary">Kembali</a>
</div>
<div class="card shadow-none border">
  <div class="card-body">
    <form action="<?= base_url('lembaga/update') ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nama</label>
          <input type="text" name="name" class="form-control" required value="<?= e($item['name']) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Induk (opsional)</label>
          <input type="number" name="parent_id" class="form-control" placeholder="ID lembaga induk" value="<?= e((string)$item['parent_id']) ?>">
        </div>
        <div class="col-md-12">
          <label class="form-label">Deskripsi</label>
          <textarea name="description" class="form-control" rows="3"><?= e((string)$item['description']) ?></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">Logo</label>
          <input type="file" name="logo" class="form-control" accept="image/*">
          <?php if (!empty($item['logo_path'])): ?>
            <div class="mt-2"><img src="<?= base_url($item['logo_path']) ?>" alt="logo" height="60"></div>
          <?php endif; ?>
        </div>
        <div class="col-md-6 d-flex align-items-end">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_keuangan" id="is_keuangan" <?= $item['is_keuangan'] ? 'checked' : '' ?>>
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