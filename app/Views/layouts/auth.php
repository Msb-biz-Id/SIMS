<?php
?>
<!doctype html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= e($pageTitle ?? 'Authentication') ?> - Platform MST</title>
	<base href="<?= base_url() ?>">
	<link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet"/>
</head>
<body class=" d-flex flex-column">
	<?= $content ?>
	<script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>
	<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</body>
</html>