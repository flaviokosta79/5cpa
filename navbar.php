<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'db/config.php';

$idConta = $_SESSION['user_id'] ?? null;

if ($idConta) {
    $stmt = $conn->prepare("SELECT * FROM contas WHERE id = :id");
    $stmt->bindParam(':id', $idConta, PDO::PARAM_STR); // O tipo de dado correto foi ajustado para STR
    $stmt->execute();
    $exibir = $stmt->fetch(PDO::FETCH_OBJ);

    if ($exibir) {
        $nome = $exibir->nome;
        $foto = isset($_SESSION['user_foto']) ? $_SESSION['user_foto'] : $exibir->file_name;
    } else {
        $nome = 'Usuário';
        $foto = null;
    }
} else {
    $nome = 'Usuário';
    $foto = null;
}

$fotoCaminho = $foto ? htmlspecialchars($foto) : 'foto_profile/default-avatar.png';

if (!file_exists($fotoCaminho)) {
    $fotoCaminho = 'foto_profile/default-avatar.png';
}
?>

<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Mensagem de boas-vindas -->
        <div class="navbar-nav align-items-center me-auto">
            <span class="nav-item nav-link">
                Olá <?php echo htmlspecialchars($nome); ?>, seja bem-vindo ao seu painel Zapbot.
            </span>
        </div>
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="nav-item lh-1 me-3">
            </li>
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="<?php echo $fotoCaminho; ?>" width="50px" height="50px" alt="Avatar" class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="profile.php">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="<?php echo $fotoCaminho; ?>" width="50px" height="50px" alt="Avatar" class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block"><?php echo htmlspecialchars($nome); ?></span>
                                    <small class="text-muted"><?php echo htmlspecialchars($_SESSION['role'] ?? ''); ?></small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="profile.php">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">Minha Conta</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="logout.php">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Sair</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
