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
                    <a href="<?= BASE_URL ?>/views/admin/reportes/reporte_ventas.php" target="_blank" class="btn btn-outline-primary mt-2">
                        <i class="bi bi-filetype-pdf"></i> Generar PDF
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dashboard-card h-100">
                    <div class="card-icon"><i class="bi bi-bag"></i></div>
                    <h5 class="fw-semibold">Reporte de pedidos</h5>
                    <p class="text-muted">Seguimiento de reservas activas, cancelaciones y tiempos de entrega.</p>
                    <a href="<?= BASE_URL ?>/views/admin/reportes/reporte_pedidos.php" target="_blank" class="btn btn-outline-primary mt-2">
                        <i class="bi bi-filetype-pdf"></i> Generar PDF
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dashboard-card h-100">
                    <div class="card-icon"><i class="bi bi-graph-up"></i></div>
                    <h5 class="fw-semibold">Reporte de inventario</h5>
                    <p class="text-muted">Niveles de stock por sucursal con alertas de reposición automática.</p>
                    <a href="<?= BASE_URL ?>/views/admin/reportes/reporte_inventario.php" target="_blank" class="btn btn-outline-primary mt-2">
                        <i class="bi bi-filetype-pdf"></i> Generar PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.dashboard-card {
    background: #fff;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    text-align: center;
    transition: all .2s ease;
}
.dashboard-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
}
.card-icon {
    font-size: 2.5rem;
    color: #0d6efd;
    margin-bottom: .75rem;
}
</style>

<?php include('includes/footer.php'); ?>
