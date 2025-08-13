<?php if ($msg = flash_get('success')): ?>
  <div class="alert alert-success" role="alert"><?= e($msg) ?></div>
<?php endif; ?>
<?php if ($msg = flash_get('error')): ?>
  <div class="alert alert-danger" role="alert"><?= e($msg) ?></div>
<?php endif; ?>
<?php if ($msg = flash_get('warning')): ?>
  <div class="alert alert-warning" role="alert"><?= e($msg) ?></div>
<?php endif; ?>