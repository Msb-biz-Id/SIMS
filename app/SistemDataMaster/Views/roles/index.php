<div class="page-header d-print-none">
	<div class="row g-2 align-items-center">
		<div class="col"><h2 class="page-title">Roles & Assignment</h2></div>
	</div>
</div>
<div class="card"><div class="card-body p-0">
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
						<a class="btn btn-sm" href="<?= base_url('roles/assign?id=' . (int)$u['id']) ?>">Assign</a>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div></div>