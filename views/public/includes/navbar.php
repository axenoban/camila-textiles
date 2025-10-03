<!-- views/public/includes/navbar.php -->
<header class="site-header">
    <nav class="navbar navbar-expand-lg navbar-light navbar-glass fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>/views/public/index.php">
                <span class="brand-mark me-2">CT</span>
                <span class="fw-semibold">Camila Textil</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNavbar" aria-controls="publicNavbar" aria-expanded="false" aria-label="Abrir navegación">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="publicNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/views/public/index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/views/public/productos.php">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/views/public/acerca.php">Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/views/public/sucursales.php">Sucursales</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/views/public/contacto.php">Contacto</a>
                    </li>
                </ul>
                <div class="ms-lg-4 mt-3 mt-lg-0">
                    <?php if (!empty($_SESSION['usuario'])): ?>
                        <?php if (($_SESSION['rol'] ?? '') === 'administrador'): ?>
                            <a class="btn btn-primary rounded-pill px-4" href="<?= BASE_URL ?>/views/admin/dashboard.php">Panel administrativo</a>
                        <?php else: ?>
                            <a class="btn btn-primary rounded-pill px-4" href="<?= BASE_URL ?>/views/cliente/dashboard.php">Mi panel</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a class="btn btn-primary rounded-pill px-4" href="<?= BASE_URL ?>/views/public/login.php">Iniciar sesión</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>
