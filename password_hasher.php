<?php
require_once __DIR__ . '/env.php';

$plainPassword = env_value('ADMIN_PASSWORD_TO_HASH', '');

if ($plainPassword === '') {
	echo 'Missing ADMIN_PASSWORD_TO_HASH in .env';
	exit;
}

$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
echo $hashedPassword;
?>