<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.php" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="assets/img/icon.png" alt="Logo">
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">zapbot.co</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item <?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active open' : '' ?>">
            <a href="index.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        <li class="menu-item <?= (basename($_SERVER['PHP_SELF']) == 'instancia.php') ? 'active open' : '' ?>">
            <a href="instancia.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-list-ul"></i>
                <div data-i18n="Instâncias">Instâncias</div>
            </a>
        </li>
        <li class="menu-item <?= (basename($_SERVER['PHP_SELF']) == 'envia_msg.php') ? 'active open' : '' ?>">
            <a href="envia_msg.php" class="menu-link">
                <i class='menu-icon tf-icons bx bxl-whatsapp-square'></i>
                <div data-i18n="Disparador">Disparador</div>
            </a>
        </li>
        <li class="menu-item <?= (basename($_SERVER['PHP_SELF']) == 'integracoes.php') ? 'active open' : '' ?>">
            <a href="integracoes.php" class="menu-link">
                <i class='menu-icon tf-icons bx bx-log-in-circle'></i>
                <div data-i18n="Integrações">Integrações</div>
            </a>
        </li>
        <li class="menu-item <?= (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active open' : '' ?>">
            <a href="profile.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Perfil">Perfil</div>
            </a>
        </li>

        <!-- Somente para usuários -->
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
        <li class="menu-item">
            <a href="logout.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-window-close"></i>
                <div data-i18n="Sair">Sair</div>
            </a>
        </li>
        <?php endif; ?>

        <!-- Somente para administradores -->
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <li class="menu-item <?= (basename($_SERVER['PHP_SELF']) == 'config_api.php') ? 'active open' : '' ?>">
            <a href="config_api.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div data-i18n="Configurações da API">Configurações da API</div>
            </a>
        </li>
        <li class="menu-item <?= (basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php') ? 'active open' : '' ?>">
            <a href="admin_dashboard.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-plus"></i>
                <div data-i18n="Admin Dashboard">Admin Dashboard</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="logout.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-window-close"></i>
                <div data-i18n="Sair">Sair</div>
            </a>
        </li>
        <?php endif; ?>

        <!-- Somente para super administradores -->
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin'): ?>
        <li class="menu-item <?= (basename($_SERVER['PHP_SELF']) == 'config_api.php') ? 'active open' : '' ?>">
            <a href="config_api.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div data-i18n="Configurações da API">Configurações da API</div>
            </a>
        </li>
        <li class="menu-item <?= (basename($_SERVER['PHP_SELF']) == 'superadmin_dashboard.php') ? 'active open' : '' ?>">
            <a href="superadmin_dashboard.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-shield-quarter"></i>
                <div data-i18n="Super Admin Dashboard">Super Admin Dashboard</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="logout.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-window-close"></i>
                <div data-i18n="Sair">Sair</div>
            </a>
        </li>
        <?php endif; ?>

        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-registered"></i>
                <div data-i18n="Painel Zapbot">Painel Zapbot</div>
            </a>
        </li>
    </ul>
</aside>
