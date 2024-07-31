<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['password']) || !isset($data['ci_id'])) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit();
}

$password = $data['password'];
$ci_id = $data['ci_id'];
$user_id = $_SESSION['user_id'];

error_log('Senha recebida: ' . $password);
error_log('CI ID recebida: ' . $ci_id);

// Verificar a senha do usuário
$stmt = $pdo->prepare('SELECT password FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Usuário não encontrado']);
    exit();
}

if (!password_verify($password, $user['password'])) {
    echo json_encode(['success' => false, 'message' => 'Senha incorreta']);
    exit();
}

// Verificar se o usuário é o criador da CI
$stmt = $pdo->prepare('SELECT * FROM cis WHERE id = ?');
$stmt->execute([$ci_id]);
$ci = $stmt->fetch();

error_log('Resultado da consulta CI: ' . print_r($ci, true));

if (!$ci) {
    echo json_encode(['success' => false, 'message' => 'CI não encontrada']);
    exit();
}

if ($ci['created_by'] != $user_id) {
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit();
}

// Deletar a CI
$stmt = $pdo->prepare('DELETE FROM cis WHERE id = ?');
$stmt->execute([$ci_id]);
echo json_encode(['success' => true]);
?>
