<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Lembaga</h6>
  <a href="<?= base_url('lembaga/create') ?>" class="btn btn-primary">Tambah Lembaga</a>
</div>
<div class="card shadow-none border">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Keuangan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $i => $it): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= e($it['name']) ?></td>
            <td><?= $it['is_keuangan'] ? 'Ya' : 'Tidak' ?></td>
            <td class="d-flex gap-2">
              <a class="btn btn-sm btn-outline-primary" href="<?= base_url('lembaga/edit?id=' . (int)$it['id']) ?>">Edit</a>
              <form action="<?= base_url('lembaga/delete') ?>" method="post" onsubmit="return confirm('Hapus data?')">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int)$it['id'] ?>">
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