<div class="page-header d-print-none">
	<div class="row g-2 align-items-center">
		<div class="col">
			<h2 class="page-title">Users</h2>
		</div>
		<div class="col-auto ms-auto d-print-none">
			<div class="btn-list">
				<a href="<?= base_url('users/import') ?>" class="btn">Import</a>
				<a href="<?= base_url('users/export') ?>" class="btn">Export</a>
				<a href="<?= base_url('users/create') ?>" class="btn btn-primary">Tambah User</a>
			</div>
		</div>
	</div>
</div>
<div class="card"><div class="card-body p-0">
	<?php require __DIR__ . '/../../ViewsPartials/_table_users.php'; ?>
</div></div>