<div class="page-header d-print-none">
	<div class="row g-2 align-items-center">
		<div class="col"><h2 class="page-title">Profil Saya</h2></div>
		<div class="col-auto ms-auto"><a href="<?= base_url('profile/edit') ?>" class="btn btn-primary">Edit Profil</a></div>
	</div>
</div>
<div class="card"><div class="card-body">
	<div class="d-flex align-items-center gap-3">
		<img src="<?= !empty($user['avatar_path']) ? base_url($user['avatar_path']) : '' ?>" alt="avatar" class="rounded" width="80" height="80">
		<div>
			<h3 class="card-title mb-1"><?= e($user['name']) ?></h3>
			<div class="text-secondary"><?= e($user['email']) ?></div>
		</div>
	</div>
</div></div>