<?php
// Replace '1234' with the actual password if different
$plainPassword = 'bploadmin2025';
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
echo $hashedPassword;
?>