<?php
// Layout utama menggunakan Tabler. Variabel: $content, $pageTitle
if (empty($_SESSION['user_id'])) {
	header('Location: ' . base_url('auth/login'));
	exit;
}
?>
<!doctype html>
<html lang="id">
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<title><?= e($pageTitle ?? 'Dashboard') ?> - <?= e(\App\Core\Settings::get('site_title', 'Platform MST')) ?></title>
		<base href="<?= base_url() ?>">
		<?php $fav = \App\Core\Settings::get('site_favicon'); ?>
		<link rel="icon" href="<?= $fav ? base_url($fav) : '' ?>">
		<link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet"/>
		<link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler-flags.min.css" rel="stylesheet"/>
		<link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler-payments.min.css" rel="stylesheet"/>
		<link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler-vendors.min.css" rel="stylesheet"/>
		<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"/>
	</head>
	<body>
		<div class="page">
			<header class="navbar navbar-expand-md d-print-none">
				<div class="container-xl">
					<?php $logo = \App\Core\Settings::get('site_logo'); $siteTitle = \App\Core\Settings::get('site_title','Platform MST'); ?>
					<?php if (!empty($logo)): ?>
						<a href="<?= base_url('dashboard') ?>" class="navbar-brand navbar-brand-autodark pe-0 me-md-3">
							<img src="<?= base_url($logo) ?>" alt="<?= e($siteTitle) ?>" class="navbar-brand-image" />
						</a>
					<?php else: ?>
						<span class="navbar-brand navbar-brand-autodark pe-0 me-md-3 mb-0"><?= e($siteTitle) ?></span>
					<?php endif; ?>
					<div class="navbar-nav flex-row order-md-last">
						<div class="nav-item dropdown">
							<a class="nav-link" href="<?= base_url('auth/logout') ?>">Keluar</a>
						</div>
					</div>
					<div class="collapse navbar-collapse" id="navbar-menu">
						<div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
							<?php require __DIR__ . '/../partials/sidebar.php'; ?>
						</div>
					</div>
				</div>
			</header>
			<div class="page-wrapper">
				<div class="container-xl">
					<?php require __DIR__ . '/../partials/flash.php'; ?>
					<div class="page-header d-print-none">
						<div class="row g-2 align-items-center">
							<div class="col">
								<h2 class="page-title"><?= e($pageTitle ?? '') ?></h2>
							</div>
						</div>
					</div>
					<div class="page-body">
						<?= $content ?>
					</div>
				</div>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
		<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>
		<script>
			$(function(){
				$("table.datatable").DataTable({
					pageLength: 10,
					language: { url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json" }
				});
			});
		</script>
	</body>
</html>