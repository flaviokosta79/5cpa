<?php
$password = 'password1';
$hashed_password = password_hash($password, PASSWORD_BCRYPT);
echo 'New hashed password for "password1": ' . $hashed_password;
?>
