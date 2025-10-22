<?php
require_once __DIR__ . '/../../models/reporte.php';

$reporteModel = new Reporte();
$metricas = $reporteModel->obtenerMetricasGenerales();
$pedidosRecientes = $reporteModel->obtenerPedidosRecientes(5);
$alertasStock = $reporteModel->obtenerProductosConBajoStock(10);
?>

<!-- views/admin/dashboard.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">

        <!-- 🧭 Encabezado -->
        <header class="page-header text-center text-lg-start mb-4">
            <h1 class="page-title mb-2">Panel de control</h1>
            <p class="page-subtitle text-muted">
                Monitorea el desempeño comercial, pedidos y alertas del sistema textil con información actualizada.
            </p>
        </header>

        <!-- 📊 Métricas generales -->
        <section class="mb-5">
            <div class="row g-4 text-center">
                <div class="col-sm-6 col-lg-3">
                    <div class="dashboard-card py-3 h-100">
                        <div class="card-icon"><i class="bi bi-box-seam"></i></div>
                        <h3 class="fw-semibold mb-1"><?= $metricas['total_productos'] ?? 0; ?></h3>
                        <p class="text-muted small mb-0">Productos activos</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="dashboard-card py-3 h-100">
                        <div class="card-icon"><i class="bi bi-cart-check"></i></div>
                        <h3 class="fw-semibold mb-1"><?= $metricas['total_pedidos'] ?? 0; ?></h3>
                        <p class="text-muted small mb-0">Pedidos registrados</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="dashboard-card py-3 h-100">
                        <div class="card-icon"><i class="bi bi-people"></i></div>
                        <h3 class="fw-semibold mb-1"><?= $metricas['total_clientes'] ?? 0; ?></h3>
                        <p class="text-muted small mb-0">Clientes registrados</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="dashboard-card py-3 h-100">
                        <div class="card-icon"><i class="bi bi-currency-dollar"></i></div>
                        <h3 class="fw-semibold mb-1">Bs <?= number_format($metricas['total_ingresos'] ?? 0, 2, ',', '.'); ?></h3>
                        <p class="text-muted small mb-0">Ingresos totales</p>
                    </div>
                </div>
            </div>


        </section>

        <!-- 🧾 Accesos rápidos -->
        <section class="mb-5">
            <div class="row g-4">
                <div class="col-12 col-xl-3">
                    <div class="dashboard-card h-100">
                        <div class="card-icon"><i class="bi bi-box"></i></div>
                        <h5 class="fw-semibold">Catálogo de productos</h5>
                        <p class="text-muted">Administra fichas técnicas, precios y disponibilidad por color.</p>
                        <a href="productos.php" class="btn btn-outline-primary mt-3">Gestionar productos</a>
                    </div>
                </div>
                <div class="col-12 col-xl-3">
                    <div class="dashboard-card h-100">
                        <div class="card-icon"><i class="bi bi-bag-check"></i></div>
                        <h5 class="fw-semibold">Pedidos activos</h5>
                        <p class="text-muted">Aprueba reservas, confirma pagos y monitorea el flujo de ventas.</p>
                        <a href="pedidos.php" class="btn btn-outline-primary mt-3">Revisar pedidos</a>
                    </div>
                </div>
                <div class="col-12 col-xl-3">
                    <div class="dashboard-card h-100">
                        <div class="card-icon"><i class="bi bi-people"></i></div>
                        <h5 class="fw-semibold">Equipo y roles</h5>
                        <p class="text-muted">Gestiona al personal, asigna roles y controla accesos al sistema.</p>
                        <a href="empleados.php" class="btn btn-outline-primary mt-3">Administrar equipo</a>
                    </div>
                </div>

                <div class="col-12 col-xl-3">
                    <div class="dashboard-card h-100">
                        <div class="card-icon"><i class="bi bi-chat-dots"></i></div>
                        <h5 class="fw-semibold">Comentarios</h5>
                        <p class="text-muted">Revisa y gestiona los comentarios enviados desde el formulario de contacto.</p>
                        <a href="comentarios.php" class="btn btn-outline-primary mt-3">Gestionar comentarios</a>
                    </div>
                </div>
            </div>

        </section>

        

        <!-- 🕓 Pedidos recientes -->
        <section class="mb-5">
            <h5 class="fw-semibold mb-3"><i class="bi bi-clock-history me-2"></i>Pedidos recientes</h5>
            <div class="portal-table">
                <?php if (!empty($pedidosRecientes)): ?>
                    <div class="table-responsive">
                        <table class="table table-modern align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Producto</th>
                                    <th>Color</th>
                                    <th>Total (Bs)</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pedidosRecientes as $pedido): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($pedido['fecha_creacion'])); ?></td>
                                        <td><?= htmlspecialchars($pedido['cliente']); ?></td>
                                        <td><?= htmlspecialchars($pedido['producto']); ?></td>
                                        <td>
                                            <span class="d-inline-flex align-items-center gap-2">
                                                <span class="color-chip" style="background: <?= htmlspecialchars($pedido['codigo_hex'] ?? '#ccc'); ?>;"></span>
                                                <?= htmlspecialchars($pedido['color']); ?>
                                            </span>
                                        </td>
                                        <td><?= number_format($pedido['total'], 2, ',', '.'); ?></td>
                                        <td>
                                            <span class="status-pill status-<?= $pedido['estado']; ?>">
                                                <?= ucfirst($pedido['estado']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted fst-italic">No hay pedidos recientes registrados.</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- ⚠️ Alertas de stock bajo -->
        <section class="mb-5">
            <h5 class="fw-semibold mb-3"><i class="bi bi-exclamation-triangle me-2 text-warning"></i>Productos con bajo stock</h5>
            <div class="portal-table">
                <?php if (!empty($alertasStock)): ?>
                    <div class="table-responsive">
                        <table class="table table-modern align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Color</th>
                                    <th>Stock (m)</th>
                                    <th>Stock (rollos)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alertasStock as $alerta): ?>
                                    <?php
                                    $nivel = ($alerta['stock_metros'] + $alerta['stock_rollos']) / 2;
                                    $color = $nivel < 3 ? 'danger' : ($nivel < 8 ? 'warning' : 'success');
                                    ?>
                                    <tr class="table-<?= $color; ?>">
                                        <td><?= htmlspecialchars($alerta['producto']); ?></td>
                                        <td>
                                            <span class="d-inline-flex align-items-center gap-2">
                                                <span class="color-chip" style="background: <?= htmlspecialchars($alerta['codigo_hex'] ?? '#999'); ?>;"></span>
                                                <?= htmlspecialchars($alerta['color']); ?>
                                            </span>
                                        </td>
                                        <td><?= number_format($alerta['stock_metros'], 2, ',', '.'); ?></td>
                                        <td><?= number_format($alerta['stock_rollos'], 2, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted fst-italic">Todos los productos tienen stock suficiente.</p>
                <?php endif; ?>
            </div>
        </section>

    </div>
</main>

<?php include('includes/footer.php'); ?>