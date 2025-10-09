<!-- views/cliente/includes/navbar.php -->
<header class="client-header">
    <nav class="navbar navbar-expand-lg client-navbar shadow-sm">
        <div class="container-fluid px-4 px-lg-5">
            <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>/views/cliente/dashboard.php">
                <span class="brand-mark me-2">CT</span>
                <span class="fw-semibold">Panel del cliente</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#clientNavbar" aria-controls="clientNavbar" aria-expanded="false" aria-label="Abrir navegación">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="clientNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/cliente/dashboard.php"><i class="bi bi-house me-2"></i>Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/cliente/productos.php"><i class="bi bi-box-seam me-2"></i>Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/cliente/pedidos.php"><i class="bi bi-clipboard-check me-2"></i>Mis pedidos</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/cliente/perfil.php"><i class="bi bi-person me-2"></i>Mi perfil</a></li>
                </ul>
                <div class="ms-lg-4 mt-3 mt-lg-0">
                    <span class="badge-user me-3"><i class="bi bi-person-circle me-2"></i><?= htmlspecialchars($clienteActual['nombre'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <a class="btn btn-outline-light rounded-pill px-4" href="<?= BASE_URL ?>/controllers/logout.php">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </nav>
</header>
