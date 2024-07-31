<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Obter informações do usuário logado
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT username, theme FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Atualizar o tema
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['theme'])) {
    $theme = $_POST['theme'];
    $stmt = $pdo->prepare('UPDATE users SET theme = ? WHERE id = ?');
    $stmt->execute([$theme, $user_id]);

    $_SESSION['theme'] = $theme;
    $_SESSION['success'] = 'Tema atualizado com sucesso.';
    header('Location: settings.php');
    exit();
}
?>

<?php include 'header.php'; ?>

<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Configurações</h4>
    
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
            <form method="POST" action="settings.php">
                <div class="form-group">
                    <label for="theme">Tema</label>
                    <select id="theme" name="theme" class="form-control">
                        <option value="light" <?php echo $user['theme'] == 'light' ? 'selected' : ''; ?>>Claro</option>
                        <option value="dark" <?php echo $user['theme'] == 'dark' ? 'selected' : ''; ?>>Escuro</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </form>
        </div>
    </div>
</div>
<!-- / Content -->

<?php include 'footer.php'; ?>
