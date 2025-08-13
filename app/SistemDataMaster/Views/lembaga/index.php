<div class="page-header d-print-none">
	<div class="row g-2 align-items-center">
		<div class="col"><h2 class="page-title">Lembaga</h2></div>
		<div class="col-auto ms-auto"><a href="<?= base_url('lembaga/create') ?>" class="btn btn-primary">Tambah Lembaga</a></div>
	</div>
</div>
<div class="card"><div class="card-body p-0">
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
						<a class="btn btn-sm" href="<?= base_url('lembaga/edit?id=' . (int)$it['id']) ?>">Edit</a>
						<form action="<?= base_url('lembaga/delete') ?>" method="post" onsubmit="return confirm('Hapus data?')">
							<?= csrf_field() ?>
							<input type="hidden" name="id" value="<?= (int)$it['id'] ?>">
							<button type="submit" class="btn btn-sm btn-danger">Hapus</button>
						</form>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div></div>