<div class="page page-center">
	<div class="container container-tight py-4">
		<div class="text-center mb-4">
			<h2 class="mt-3">Reset Password</h2>
			<p class="text-secondary">Masukkan password baru Anda</p>
		</div>
		<?php if (!empty($_SESSION['error'])): ?>
			<div class="alert alert-danger" role="alert"><?= e($_SESSION['error']) ?></div>
			<?php $_SESSION['error'] = null; endif; ?>
		<div class="card card-md">
			<div class="card-body">
				<form action="<?= base_url('auth/do-reset-password') ?>" method="post">
					<?= csrf_field() ?>
					<input type="hidden" name="token" value="<?= e($token) ?>">
					<div class="mb-3">
						<label class="form-label">Password baru</label>
						<input type="password" name="password" class="form-control" placeholder="Password baru" required>
					</div>
					<div class="form-footer">
						<button type="submit" class="btn btn-primary w-100">Ubah Password</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>