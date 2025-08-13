<div class="page-header d-print-none">
	<div class="row g-2 align-items-center">
		<div class="col"><h2 class="page-title">Assign Roles: <?= e($user['name']) ?></h2></div>
		<div class="col-auto ms-auto"><a href="<?= base_url('roles') ?>" class="btn">Kembali</a></div>
	</div>
</div>
<div class="card"><div class="card-body">
	<form action="<?= base_url('roles/save-assignment') ?>" method="post">
		<?= csrf_field() ?>
		<input type="hidden" name="user_id" value="<?= (int)$user['id'] ?>">
		<div class="row g-2">
			<?php foreach ($roles as $role): $checked = in_array($role['name'], $userRoleNames, true); ?>
			<div class="col-md-3">
				<label class="form-check">
					<input class="form-check-input" type="checkbox" name="roles[]" value="<?= (int)$role['id'] ?>" <?= $checked ? 'checked' : '' ?>>
					<span class="form-check-label"><?= e($role['name']) ?></span>
				</label>
			</div>
			<?php endforeach; ?>
		</div>
		<div class="mt-3"><button type="submit" class="btn btn-primary">Simpan</button></div>
	</form>
</div></div>