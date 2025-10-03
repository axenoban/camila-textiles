<?php
require_once __DIR__ . '/../../models/reporte.php';

$reporteModel = new Reporte();
$metricas = $reporteModel->obtenerMetricasGenerales();
$pedidosRecientes = $reporteModel->obtenerPedidosRecientes(5);
$alertasStock = $reporteModel->obtenerProductosConBajoStock();
?>
<!-- views/admin/dashboard.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <header class="page-header text-center text-lg-start">
            <h1 class="page-title">Panel de control</h1>
            <p class="page-subtitle">Monitorea el desempeño comercial, pedidos y equipo de Camila Textil desde una interfaz clara e intuitiva.</p>
        </header>
        <section class="metrics-grid">
            <article class="metric-card">
                <div class="metric-icon gradient-blue"><i class="bi bi-box"></i></div>
                <div>
                    <h2 class="metric-value"><?= number_format($metricas['productos']); ?></h2>
                    <p class="metric-label">Productos publicados</p>
                </div>
                <a href="productos.php" class="metric-link">Gestionar catálogo</a>
            </article>
            <article class="metric-card">
                <div class="metric-icon gradient-green"><i class="bi bi-people"></i></div>
                <div>
                    <h2 class="metric-value"><?= number_format($metricas['clientes']); ?></h2>
                    <p class="metric-label">Clientes registrados</p>
                </div>
                <a href="empleados.php" class="metric-link">Gestionar usuarios</a>
            </article>
            <article class="metric-card">
                <div class="metric-icon gradient-orange"><i class="bi bi-truck"></i></div>
                <div>
                    <h2 class="metric-value"><?= number_format($metricas['pedidosPendientes']); ?></h2>
                    <p class="metric-label">Pedidos pendientes</p>
                </div>
                <a href="pedidos.php" class="metric-link">Revisar pedidos</a>
            </article>
            <article class="metric-card">
                <div class="metric-icon gradient-purple"><i class="bi bi-currency-dollar"></i></div>
                <div>
                    <h2 class="metric-value">$<?= number_format($metricas['ingresos'], 2); ?></h2>
                    <p class="metric-label">Ingresos estimados</p>
                </div>
                <span class="metric-link disabled">Actualizado en tiempo real</span>
            </article>
        </section>

        <div class="row g-4 mt-1">
            <div class="col-12 col-xl-7">
                <div class="table-shell h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h2 class="h5 fw-semibold mb-1">Pedidos recientes</h2>
                            <p class="text-muted mb-0">Seguimiento de los últimos movimientos registrados en el sistema.</p>
                        </div>
                        <a href="pedidos.php" class="btn btn-outline-primary btn-sm">Ver todos</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-modern align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Producto</th>
                                    <th>Cant.</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($pedidosRecientes)): ?>
                                    <?php foreach ($pedidosRecientes as $pedido): ?>
                                        <tr>
                                            <td>#<?= $pedido['id']; ?></td>
                                            <td><?= htmlspecialchars($pedido['cliente'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= htmlspecialchars($pedido['producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= $pedido['cantidad']; ?></td>
                                            <td><span class="status-pill status-<?= htmlspecialchars($pedido['estado'], ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars(ucfirst($pedido['estado']), ENT_QUOTES, 'UTF-8'); ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No se registran pedidos todavía.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-5">
                <div class="dashboard-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h2 class="h5 fw-semibold mb-1">Alertas de inventario</h2>
                            <p class="text-muted mb-0">Productos con existencias por debajo del umbral recomendado.</p>
                        </div>
                        <span class="badge bg-soft-primary text-primary">Stock total: <?= number_format($metricas['stockDisponible']); ?></span>
                    </div>
                    <?php if (!empty($alertasStock)): ?>
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($alertasStock as $alerta): ?>
                                <li class="inventory-alert">
                                    <span class="alert-dot"></span>
                                    <div>
                                        <p class="mb-0 fw-semibold"><?= htmlspecialchars($alerta['nombre'], ENT_QUOTES, 'UTF-8'); ?></p>
                                        <small class="text-muted"><?= $alerta['cantidad']; ?> unidades disponibles</small>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-check-circle display-6 d-block mb-3 text-success"></i>
                            <p class="mb-0">No hay productos en alerta actualmente.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
