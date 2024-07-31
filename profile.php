<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Obter os dados do usuário logado
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT username, email, whatsapp, profile_picture FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$profile_picture = !empty($user['profile_picture']) ? "uploads/" . htmlspecialchars($user['profile_picture']) : "assets/img/default.png";
?>

<?php include 'header.php'; ?>

<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Dados Pessoais</h4>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" action="update_profile.php" enctype="multipart/form-data">
                <div class="d-flex align-items-center mb-4">
                    <img src="<?php echo $profile_picture; ?>" alt="Foto de Perfil" class="rounded-circle me-3" style="width: 150px; height: 150px; object-fit: cover;">
                    <div>
                        <input type="file" class="form-control mb-2" id="profile_picture" name="profile_picture">
                        <button type="submit" name="action" value="update_photo" class="btn btn-primary">Salvar Foto</button>
                    </div>
                </div>
            </form>
            <form method="POST" action="update_profile.php">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="whatsapp" class="form-label">WhatsApp</label>
                        <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="<?php echo isset($user['whatsapp']) ? htmlspecialchars($user['whatsapp']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="current_password" class="form-label">Senha Atual</label>
                        <input type="password" class="form-control" id="current_password" name="current_password">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Nova Senha</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="confirm_password" class="form-label">Confirmar Nova Senha</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>
                </div>
                <button type="submit" name="action" value="update_info" class="btn btn-primary">Alterar dados</button>
            </form>
        </div>
    </div>
</div>
<!-- / Content -->

<?php include 'footer.php'; ?>
