<!-- views/public/includes/navbar.php -->
<header class="site-header">
    <nav class="navbar navbar-expand-lg navbar-light navbar-glass fixed-top">
        <div class="container">
            <?php
            $homeUrl = BASE_URL . '/views/public/index.php';
            $productosUrl = BASE_URL . '/views/public/productos.php';
            $nosotrosUrl = BASE_URL . '/views/public/acerca.php';
            $sucursalesUrl = BASE_URL . '/views/public/sucursales.php';
            $contactoUrl = BASE_URL . '/views/public/contacto.php';

            $usuarioSesion = $_SESSION['usuario'] ?? null;
            $rolSesion = $_SESSION['rol'] ?? '';
            $panelUrl = $rolSesion === 'administrador'
                ? BASE_URL . '/views/admin/dashboard.php'
                : BASE_URL . '/views/cliente/dashboard.php';
            ?>
            <a class="navbar-brand d-flex align-items-center" href="<?= $homeUrl; ?>">
                <span class="brand-mark me-2">CT</span>
                <span class="fw-semibold">Camila Textil</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNavbar" aria-controls="publicNavbar" aria-expanded="false" aria-label="Abrir navegación">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="publicNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $homeUrl; ?>">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $productosUrl; ?>">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $nosotrosUrl; ?>">Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $sucursalesUrl; ?>">Sucursales</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $contactoUrl; ?>">Contacto</a>
                    </li>
                </ul>
                <div class="ms-lg-4 mt-3 mt-lg-0 d-flex align-items-center gap-3">
                    <?php if ($usuarioSesion): ?>
                        <span class="text-white-50 small">Hola, <?= htmlspecialchars($usuarioSesion['nombre'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <a class="btn btn-outline-light rounded-pill px-3" href="<?= $panelUrl; ?>">
                            <?= $rolSesion === 'administrador' ? 'Panel administrativo' : 'Mi panel'; ?>
                        </a>
                        <a class="btn btn-primary rounded-pill px-3" href="<?= BASE_URL ?>/controllers/logout.php">Cerrar sesión</a>
                    <?php else: ?>
                        <a class="btn btn-primary rounded-pill px-4" href="<?= BASE_URL ?>/views/public/login.php">Iniciar sesión</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>
