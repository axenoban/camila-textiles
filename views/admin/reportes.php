<!-- views/admin/reportes.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <header class="page-header text-center text-lg-start">
            <h1 class="page-title">Reportes estratégicos</h1>
            <p class="page-subtitle">Genera informes clave para analizar ventas, pedidos y rotación de inventarios en segundos.</p>
        </header>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="dashboard-card h-100">
                    <div class="card-icon"><i class="bi bi-receipt"></i></div>
                    <h5 class="fw-semibold">Reporte de ventas</h5>
                    <p class="text-muted">Resumen por periodo, top de telas vendidas y margen obtenido.</p>
                    <a href="#" class="btn btn-outline-primary mt-2">Generar</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card h-100">
                    <div class="card-icon"><i class="bi bi-bag"></i></div>
                    <h5 class="fw-semibold">Reporte de pedidos</h5>
                    <p class="text-muted">Seguimiento de reservas activas, cancelaciones y tiempos de entrega.</p>
                    <a href="#" class="btn btn-outline-primary mt-2">Generar</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card h-100">
                    <div class="card-icon"><i class="bi bi-graph-up"></i></div>
                    <h5 class="fw-semibold">Reporte de inventario</h5>
                    <p class="text-muted">Niveles de stock por sucursal con alertas de reposición automática.</p>
                    <a href="#" class="btn btn-outline-primary mt-2">Generar</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
