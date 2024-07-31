<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'db.php';

// Definir o tema padrão se não estiver definido
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}

// Obter informações do usuário logado
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT username, profile_picture, theme FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard 5CPA</title>
    <link rel="stylesheet" href="assets/vendor/css/core.css">
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css">
    <link rel="stylesheet" href="assets/css/demo.css">
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/css/themes/theme-dark.css" id="theme-dark" disabled>
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
    <style>
        .app-brand {
            padding: 110px 0;
        }
        .app-brand-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }
        .app-brand-logo img {
            max-width: 100%;
            max-height: 180px;
            margin-left: 40px;
            margin-right: auto;
        }
        .app-brand-text {
            font-size: 1.5rem;
        }
        .menu-inner {
            margin-top: -20px;
        }
        .logo-left .app-brand-logo, .logo-left .app-brand-text {
            align-items: flex-start;
        }
        .logo-right .app-brand-logo, .logo-right .app-brand-text {
            align-items: flex-end;
        }
    </style>
</head>
<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme logo-left">
                <div class="app-brand demo">
                    <a href="dashboard.php" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <img src="assets/img/5cpa.png" alt="Logo">
                        </span>
                    </a>
                </div>
                <div class="menu-inner-shadow"></div>
                <ul class="menu-inner py-1">
                    <li class="menu-item active">
                        <a href="dashboard.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Analytics">Dashboard</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="generate_ci.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-layout"></i>
                            <div data-i18n="Layouts">Gerar Número de CI</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="inform_folga.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-calendar"></i>
                            <div data-i18n="Inform Folga">Informar Folga Semanal</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="profile.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-user"></i>
                            <div data-i18n="Profile">Perfil</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="settings.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-cog"></i>
                            <div data-i18n="Settings">Configurações</div>
                        </a>
                    </li>
                </ul>
            </aside>
            <!-- / Menu -->
            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>
                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <?php
                        date_default_timezone_set('America/Sao_Paulo');

                        function get_greeting() {
                            $hour = date('H');
                            if ($hour < 12) {
                                return 'Bom dia';
                            } elseif ($hour < 18) {
                                return 'Boa tarde';
                            } else {
                                return 'Boa noite';
                            }
                        }

                        $profile_picture = !empty($user['profile_picture']) ? "uploads/" . htmlspecialchars($user['profile_picture']) : "assets/img/default.png";
                        ?>
                        <span class="navbar-text me-3">
                            <?php echo get_greeting(); ?>, <?php echo htmlspecialchars($user['username']); ?>! seja bem-vindo ao painel do 5° CPA.
                        </span>
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="#" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="<?php echo $profile_picture; ?>" alt="Avatar" class="w-px-40 h-auto rounded-circle">
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="profile.php">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">Perfil</span>
                                        </a>
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
                <!-- / Navbar -->
                <div class="content-wrapper">
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            var theme = "<?php echo $_SESSION['theme'] ?? 'light'; ?>";
                            if (theme === 'dark') {
                                document.getElementById('theme-dark').removeAttribute('disabled');
                            }
                        });
                    </script>
