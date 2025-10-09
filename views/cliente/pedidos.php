<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/../../models/pedido.php';

$pedidoModel = new Pedido();
$pedidosCliente = $pedidoModel->obtenerPedidosPorCliente($clienteActual['id']);

$pedidosCliente = array_map(function ($pedido) {
    $unidad = $pedido['unidad'] ?? 'metro';
    $cantidad = (float) ($pedido['cantidad'] ?? 0);
    $presentacion = $pedido['presentacion_tipo'] ?? $unidad;
    $metrosUnidad = (int) round($pedido['metros_por_unidad'] ?? 0);

    if ($unidad === 'rollo' && $cantidad > 0) {
        $cantidadEntera = (int) $cantidad;
        $cantidadLegible = $cantidadEntera . ' ' . ($cantidadEntera === 1 ? 'rollo' : 'rollos');
        if ($metrosUnidad > 0) {
            $cantidadLegible .= ' · ' . $metrosUnidad . ' m c/u';
        }
    } else {
        $cantidadLegible = rtrim(rtrim(number_format($cantidad, 2, '.', ''), '0'), '.');
        if ($cantidadLegible === '') {
            $cantidadLegible = '0';
        }
        $cantidadLegible .= ' ' . ((float) $cantidad === 1.0 ? 'metro' : 'metros');
    }

    $pedido['cantidad_legible'] = $cantidadLegible;
    $pedido['presentacion_legible'] = ucfirst($presentacion);
    $pedido['color_legible'] = $pedido['color_nombre'] ?? 'A confirmar';
    $pedido['precio_unitario_formateado'] = number_format((float) ($pedido['precio_unitario'] ?? 0), 2);
    $pedido['total_formateado'] = number_format((float) ($pedido['total'] ?? 0), 2);

    return $pedido;
}, $pedidosCliente);

$pedidosActivos = array_values(array_filter($pedidosCliente, function ($pedido) {
    return in_array($pedido['estado'], ['pendiente', 'confirmado'], true);
}));

$pedidosHistoricos = array_map(function ($pedido) {
    $pedido['fecha_formateada'] = date('d/m/Y', strtotime($pedido['fecha_creacion']));
    return $pedido;
}, array_values(array_filter($pedidosCliente, function ($pedido) {
    return in_array($pedido['estado'], ['completado', 'cancelado'], true);
})));

$mensajeReserva = $_SESSION['reserva_mensaje'] ?? null;
$tipoReserva = $_SESSION['reserva_tipo'] ?? null;
unset($_SESSION['reserva_mensaje'], $_SESSION['reserva_tipo']);
?>
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-area">
    <div class="container-fluid px-4 px-lg-5">
        <section class="client-section text-center text-lg-start">
            <h1 class="section-heading">Mis pedidos</h1>
            <p class="section-subtitle">Supervisa tus pedidos activos y consulta tu historial en un panel claro y accesible.</p>
        </section>
        <?php if ($mensajeReserva): ?>
            <div class="alert alert-<?= $tipoReserva === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert" data-auto-dismiss="true">
                <?= htmlspecialchars($mensajeReserva, ENT_QUOTES, 'UTF-8'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>
        <div class="client-section">
            <h2 class="h5 fw-semibold mb-3">Pedidos actuales</h2>
            <div class="portal-table">
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID Pedido</th>
                                <th>Producto</th>
                                <th>Presentación</th>
                                <th>Color</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th>Estado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pedidosActivos)): ?>
                                <?php foreach ($pedidosActivos as $pedido): ?>
                                <tr>
                                    <td><?= (int) $pedido['id']; ?></td>
                                    <td><?= htmlspecialchars($pedido['producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($pedido['presentacion_legible'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <span class="badge-soft"><?= htmlspecialchars($pedido['color_legible'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($pedido['cantidad_legible'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>Bs <?= htmlspecialchars($pedido['total_formateado'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <span class="status-pill status-<?= htmlspecialchars($pedido['estado'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <?= htmlspecialchars(ucfirst($pedido['estado']), ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#detalle-pedido-<?= (int) $pedido['id']; ?>" aria-expanded="false" aria-controls="detalle-pedido-<?= (int) $pedido['id']; ?>">
                                            Mostrar detalles
                                        </button>
                                    </td>
                                </tr>
                                <tr class="collapse" id="detalle-pedido-<?= (int) $pedido['id']; ?>">
                                    <td colspan="8" class="bg-light">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <div class="small text-muted">Precio unitario</div>
                                                <strong>Bs <?= htmlspecialchars($pedido['precio_unitario_formateado'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="small text-muted">Color seleccionado</div>
                                                <span class="badge bg-secondary-subtle text-dark"><?= htmlspecialchars($pedido['color_legible'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="small text-muted">Presentación</div>
                                                <span class="badge bg-secondary-subtle text-dark"><?= htmlspecialchars($pedido['presentacion_legible'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="small text-muted">Estado actual</div>
                                                <span class="status-pill status-<?= htmlspecialchars($pedido['estado'], ENT_QUOTES, 'UTF-8'); ?>">
                                                    <?= htmlspecialchars(ucfirst($pedido['estado']), ENT_QUOTES, 'UTF-8'); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Aún no tienes pedidos activos. Reserva tu próxima tela desde el catálogo.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="client-section">
            <h2 class="h5 fw-semibold mb-3">Historial de pedidos</h2>
            <div class="portal-table">
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID Pedido</th>
                                <th>Producto</th>
                                <th>Presentación</th>
                                <th>Color</th>
                                <th>Cantidad</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pedidosHistoricos)): ?>
                                <?php foreach ($pedidosHistoricos as $pedido): ?>
                                <tr>
                                    <td><?= (int) $pedido['id']; ?></td>
                                    <td><?= htmlspecialchars($pedido['producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($pedido['presentacion_legible'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><span class="badge-soft"><?= htmlspecialchars($pedido['color_legible'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                    <td><?= htmlspecialchars($pedido['cantidad_legible'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>Bs <?= htmlspecialchars($pedido['total_formateado'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars(ucfirst($pedido['estado']), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($pedido['fecha_formateada'], ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Aquí aparecerá tu historial en cuanto completes tus primeros pedidos.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
