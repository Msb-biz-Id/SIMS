<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Roles & Assignment</h6>
</div>
<div class="card shadow-none border">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Roles</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $i => $u): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= e($u['name']) ?></td>
            <td><?= e($u['email']) ?></td>
            <td><?= e(implode(', ', $userRoles[$u['id']] ?? [])) ?></td>
            <td>
              <a class="btn btn-sm btn-outline-primary" href="<?= base_url('roles/assign?id=' . (int)$u['id']) ?>">Assign</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>