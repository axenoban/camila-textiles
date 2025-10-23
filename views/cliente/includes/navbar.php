<!-- views/cliente/includes/navbar.php -->
 <header class="client-header shadow-sm">
    <nav class="navbar navbar-expand-lg client-navbar">
        <div class="container-fluid px-4 px-lg-5">
            <!-- Branding -->
            <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>/views/cliente/dashboard.php">
                <span class="brand-mark me-2">CT</span>
                <span class="fw-semibold">Camila Textil</span>
            </a>

            <!-- Toggle -->
            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#clientNavbar" aria-controls="clientNavbar" aria-expanded="false" aria-label="Abrir navegación">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu -->
            <div class="collapse navbar-collapse" id="clientNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/cliente/dashboard.php"><i class="bi bi-house me-2"></i>Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/cliente/productos.php"><i class="bi bi-box-seam me-2"></i>Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/cliente/pedidos.php"><i class="bi bi-clipboard-check me-2"></i>Mis pedidos</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/cliente/perfil.php"><i class="bi bi-person me-2"></i>Mi perfil</a></li>
                </ul>

                <!-- Perfil usuario -->
                <div class="ms-lg-4 mt-3 mt-lg-0 d-flex align-items-center gap-3">
                    <span class="text-white-50 d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle"></i>
                        <?= htmlspecialchars($clienteActual['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                    <a class="btn btn-outline-light rounded-pill px-4" href="<?= BASE_URL ?>/controllers/logout.php">
                        <i class="bi bi-box-arrow-right me-1"></i>Salir
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>