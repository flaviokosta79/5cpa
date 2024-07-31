<?php
$password = 'password1';
$hashed_password = '$2y$10$2nleJRCMzzes/chIikpbVuIFliPbHQG.MHXrlUvSllFJgoR/DgdAi';

if (password_verify($password, $hashed_password)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}
?>
