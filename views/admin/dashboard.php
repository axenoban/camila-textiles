<!-- views/admin/dashboard.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <header class="page-header text-center text-lg-start">
            <h1 class="page-title">Panel de control</h1>
            <p class="page-subtitle">Monitorea el desempeño comercial, pedidos y equipo de Camila Textil desde una interfaz clara e intuitiva.</p>
        </header>
        <div class="row g-4">
            <div class="col-12 col-xl-4">
                <div class="dashboard-card h-100">
                    <div class="card-icon"><i class="bi bi-box"></i></div>
                    <h5 class="fw-semibold">Catálogo de productos</h5>
                    <p class="text-muted">Administra descripciones, precios y disponibilidad de cada referencia.</p>
                    <a href="productos.php" class="btn btn-outline-primary mt-3">Gestionar productos</a>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="dashboard-card h-100">
                    <div class="card-icon"><i class="bi bi-bag-check"></i></div>
                    <h5 class="fw-semibold">Pedidos activos</h5>
                    <p class="text-muted">Aprueba reservas, prepara envíos y da seguimiento a cada venta en curso.</p>
                    <a href="pedidos.php" class="btn btn-outline-primary mt-3">Revisar pedidos</a>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="dashboard-card h-100">
                    <div class="card-icon"><i class="bi bi-people"></i></div>
                    <h5 class="fw-semibold">Equipo y roles</h5>
                    <p class="text-muted">Gestiona al personal, asigna responsabilidades y controla accesos.</p>
                    <a href="empleados.php" class="btn btn-outline-primary mt-3">Administrar equipo</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
