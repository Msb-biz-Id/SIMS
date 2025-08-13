<div class="card shadow-none border">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $i => $u): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= e($u['name']) ?></td>
            <td><?= e($u['email']) ?></td>
            <td class="d-flex gap-2">
              <a class="btn btn-sm btn-outline-primary" href="<?= base_url('users/edit?id=' . (int)$u['id']) ?>">Edit</a>
              <form action="<?= base_url('users/delete') ?>" method="post" onsubmit="return confirm('Hapus user?')">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
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