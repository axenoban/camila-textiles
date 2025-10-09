<!-- views/admin/includes/navbar.php -->
<header class="admin-header">
    <nav class="navbar navbar-expand-lg admin-navbar shadow-sm">
        <div class="container-fluid px-4 px-lg-5">
            <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
                <span class="brand-mark me-2">CT</span>
                <span class="fw-semibold">Panel administrativo</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Abrir navegación">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="productos.php"><i class="bi bi-box-seam me-2"></i>Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="inventario.php"><i class="bi bi-clipboard-data me-2"></i>Inventario</a></li>
                    <li class="nav-item"><a class="nav-link" href="pedidos.php"><i class="bi bi-bag-check me-2"></i>Pedidos</a></li>
                    <li class="nav-item"><a class="nav-link" href="empleados.php"><i class="bi bi-people me-2"></i>Empleados</a></li>
                    <li class="nav-item"><a class="nav-link" href="sucursales.php"><i class="bi bi-geo-alt me-2"></i>Sucursales</a></li>
                    <li class="nav-item"><a class="nav-link" href="reportes.php"><i class="bi bi-bar-chart-line me-2"></i>Reportes</a></li>
                </ul>
                <div class="ms-lg-4 mt-3 mt-lg-0 d-flex align-items-center gap-3">
                    <span class="badge-user"><i class="bi bi-person-circle me-2"></i>Administrador</span>
                    <a class="btn btn-outline-light rounded-pill px-4" href="<?= BASE_URL ?>/controllers/logout.php">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </nav>
</header>
