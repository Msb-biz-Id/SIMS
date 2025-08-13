<div class="page page-center">
	<div class="container container-tight py-4">
		<div class="text-center mb-4">
			<h2>Masuk</h2>
			<p class="text-secondary">Silakan isi kredensial Anda</p>
		</div>
		<?php if (!empty($_SESSION['error'])): ?>
			<div class="alert alert-danger" role="alert"><?= e($_SESSION['error']) ?></div>
			<?php $_SESSION['error'] = null; endif; ?>
		<div class="card card-md">
			<div class="card-body">
				<form action="<?= base_url('auth/do-login') ?>" method="post" autocomplete="off">
					<?= csrf_field() ?>
					<div class="mb-3">
						<label class="form-label">Email</label>
						<input type="email" name="email" class="form-control" placeholder="you@example.com" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Password</label>
						<input type="password" name="password" class="form-control" placeholder="Password" required>
					</div>
					<div class="mb-3">
						<div class="cf-turnstile" data-sitekey="<?= e($turnstile_site_key) ?>"></div>
					</div>
					<div class="form-footer">
						<button type="submit" class="btn btn-primary w-100">Masuk</button>
					</div>
				</form>
			</div>
		</div>
		<div class="text-center text-secondary mt-3">
			<a href="<?= base_url('auth/forgot-password') ?>">Lupa Password?</a>
		</div>
	</div>
</div>