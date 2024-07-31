<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Obter informações do usuário logado
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT username FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<?php include 'header.php'; ?>

<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Olá <?php echo htmlspecialchars($user['username']); ?>, seja bem-vindo ao seu painel 5CPA.</h4>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Gerar Número de CI</h5>
                    <p class="card-text">Gere e gerencie seus números de CI.</p>
                    <a href="generate_ci.php" class="btn btn-primary">Acessar</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Perfil</h5>
                    <p class="card-text">Gerencie seu perfil.</p>
                    <a href="profile.php" class="btn btn-primary">Acessar</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Configurações</h5>
                    <p class="card-text">Configure suas preferências.</p>
                    <a href="settings.php" class="btn btn-primary">Acessar</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informar Folga Semanal</h5>
                    <p class="card-text">Informe o dia de folga à P1.</p>
                    <a href="inform_folga.php" class="btn btn-primary">Acessar</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->

<?php include 'footer.php'; ?>
