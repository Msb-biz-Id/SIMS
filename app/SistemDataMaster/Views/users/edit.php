<div class="page-header d-print-none">
	<div class="row g-2 align-items-center">
		<div class="col"><h2 class="page-title">Edit User</h2></div>
		<div class="col-auto ms-auto"><a href="<?= base_url('users') ?>" class="btn">Kembali</a></div>
	</div>
</div>
<div class="card"><div class="card-body">
	<form action="<?= base_url('users/update') ?>" method="post" enctype="multipart/form-data">
		<?= csrf_field() ?>
		<input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
		<div class="row g-3">
			<div class="col-md-6">
				<label class="form-label">Nama</label>
				<input type="text" name="name" class="form-control" required value="<?= e($user['name']) ?>">
			</div>
			<div class="col-md-6">
				<label class="form-label">Email</label>
				<input type="email" name="email" class="form-control" required value="<?= e($user['email']) ?>">
			</div>
			<div class="col-md-6">
				<label class="form-label">Password (kosongkan bila tidak diubah)</label>
				<input type="password" name="password" class="form-control">
			</div>
			<div class="col-md-6">
				<label class="form-label">Avatar</label>
				<input type="file" name="avatar" class="form-control" accept="image/*">
				<?php if (!empty($user['avatar_path'])): ?>
					<div class="mt-2"><img src="<?= base_url($user['avatar_path']) ?>" alt="avatar" height="60"></div>
				<?php endif; ?>
			</div>
			<div class="col-12">
				<label class="form-label">Roles</label>
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
			</div>
			<div class="col-12">
				<label class="form-label">Akses Lembaga</label>
				<div class="row g-2">
					<?php foreach ($lembaga as $l): $checked = in_array((int)$l['id'], $userLembagaIds, true); ?>
					<div class="col-md-4">
						<label class="form-check">
							<input class="form-check-input" type="checkbox" name="lembaga_ids[]" value="<?= (int)$l['id'] ?>" <?= $checked ? 'checked' : '' ?>>
							<span class="form-check-label"><?= e($l['name']) ?><?= $l['is_keuangan'] ? ' (Keuangan)' : '' ?></span>
						</label>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="mt-3"><button type="submit" class="btn btn-primary">Simpan</button></div>
	</form>
</div></div>