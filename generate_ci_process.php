<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Definir o fuso horário para garantir que a hora do servidor esteja correta
date_default_timezone_set('America/Sao_Paulo');

// Obter informações do usuário logado
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT username FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();
$username = $user['username']; // Nome do usuário logado

// Função para gerar número de CI único
function generateUniqueCiNumber($pdo) {
    do {
        $stmt = $pdo->prepare('SELECT MAX(ci_number) FROM cis WHERE YEAR(date) = YEAR(CURDATE())');
        $stmt->execute();
        $last_number = $stmt->fetchColumn();
        $new_number = $last_number ? $last_number + 1 : 1;
        if ($new_number > 9999) {
            $new_number = 1;
        }

        // Verificar se o número de CI já existe
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM cis WHERE ci_number = ? AND YEAR(date) = YEAR(CURDATE())');
        $stmt->execute([$new_number]);
        $count = $stmt->fetchColumn();
    } while ($count > 0);

    return $new_number;
}

// Processar o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = $_POST['description'] === "" ? $_POST['description_other'] : $_POST['description'];
    $origem = $_POST['origem'];
    $destinations = $_POST['destinations'];
    $date = $_POST['date'];
    $ci_number = generateUniqueCiNumber($pdo);

    // Inserir o novo CI no banco de dados
    try {
        $stmt = $pdo->prepare('INSERT INTO cis (ci_number, description, origem, destination, date, user_id, username) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$ci_number, $description, $origem, $destinations, $date, $user_id, $username]);

        $_SESSION['success'] = 'Número de CI gerado com sucesso.';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Erro ao gerar número de CI: ' . $e->getMessage();
    }

    header('Location: generate_ci.php');
    exit();
}
?>
