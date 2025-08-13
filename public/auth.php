<?php
header('Location: ' . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/index.php/auth/login');
exit;