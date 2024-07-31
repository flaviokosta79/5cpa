<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_POST['action'] == 'update_photo' && isset($_FILES['profile_picture'])) {
    $profile_picture = $_FILES['profile_picture'];
    if ($profile_picture['size'] > 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profile_picture["name"]);
        move_uploaded_file($profile_picture["tmp_name"], $target_file);
        $profile_picture_name = basename($profile_picture["name"]);
        $stmt = $pdo->prepare('UPDATE users SET profile_picture = ? WHERE id = ?');
        $stmt->execute([$profile_picture_name, $user_id]);
        $_SESSION['success'] = 'Foto de perfil atualizada com sucesso.';
        header('Location: profile.php');
        exit();
    }
}

if ($_POST['action'] == 'update_info') {
    $email = $_POST['email'];
    $whatsapp = $_POST['whatsapp'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $current_password = $_POST['current_password'];

    if (!empty($password) && $password !== $confirm_password) {
        $_SESSION['error'] = 'As senhas não correspondem.';
        header('Location: profile.php');
        exit();
    }

    // Verificar a senha atual antes de atualizar
    $stmt = $pdo->prepare('SELECT password FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!password_verify($current_password, $user['password'])) {
        $_SESSION['error'] = 'Senha atual incorreta.';
        header('Location: profile.php');
        exit();
    }

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE users SET email = ?, whatsapp = ?, password = ? WHERE id = ?');
        $stmt->execute([$email, $whatsapp, $hashed_password, $user_id]);
    } else {
        $stmt = $pdo->prepare('UPDATE users SET email = ?, whatsapp = ? WHERE id = ?');
        $stmt->execute([$email, $whatsapp, $user_id]);
    }

    $_SESSION['success'] = 'Perfil atualizado com sucesso.';
    header('Location: profile.php');
    exit();
}
?>
