<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/../../models/pedido.php';

$pedidoModel = new Pedido();
$pedidosCliente = $pedidoModel->obtenerPedidosPorCliente($clienteActual['id']);

$pedidosCliente = array_map(function ($pedido) {
    $pedido['total_formateado'] = number_format((float) ($pedido['total'] ?? 0), 2);
    $pedido['fecha_formateada'] = date('d/m/Y', strtotime($pedido['fecha_creacion']));
    $pedido['detalles'] = array_map(function ($detalle) {
        $unidad = $detalle['unidad'] ?? $detalle['presentacion_tipo'] ?? 'metro';
        $cantidad = (float) ($detalle['cantidad'] ?? 0);
        $metrosUnidad = (float) ($detalle['metros_por_unidad'] ?? 0);

        if ($unidad === 'rollo' && $cantidad > 0) {
            $cantidadEntera = (int) round($cantidad);
            $detalle['cantidad_legible'] = $cantidadEntera . ' ' . ($cantidadEntera === 1 ? 'rollo' : 'rollos');
            if ($metrosUnidad > 0) {
                $detalle['cantidad_legible'] .= ' · ' . (int) round($metrosUnidad) . ' m c/u';
            }
        } else {
            $cantidadFormateada = rtrim(rtrim(number_format($cantidad, 2, '.', ''), '0'), '.');
            if ($cantidadFormateada === '') {
                $cantidadFormateada = '0';
            }
            $detalle['cantidad_legible'] = $cantidadFormateada . ' ' . ($cantidad == 1.0 ? 'metro' : 'metros');
        }

        $detalle['presentacion_legible'] = ucfirst($detalle['presentacion_tipo'] ?? $unidad);
        $detalle['color_legible'] = $detalle['color_nombre'] ?? 'A confirmar';
        $detalle['precio_unitario_formateado'] = number_format((float) ($detalle['precio_unitario'] ?? 0), 2);
        $detalle['subtotal_formateado'] = number_format((float) ($detalle['subtotal'] ?? 0), 2);

        return $detalle;
    }, $pedido['detalles'] ?? []);

    return $pedido;
}, $pedidosCliente);

$pedidosActivos = array_values(array_filter($pedidosCliente, function ($pedido) {
    return in_array($pedido['estado'], ['pendiente', 'confirmado'], true);
}));

$pedidosHistoricos = array_values(array_filter($pedidosCliente, function ($pedido) {
    return in_array($pedido['estado'], ['completado', 'cancelado'], true);
}));

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

        <?php if ($mensajeReserva) : ?>
            <div class="alert alert-<?= $tipoReserva === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert" data-auto-dismiss="true">
                <?= htmlspecialchars($mensajeReserva, ENT_QUOTES, 'UTF-8'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

        <div class="client-section">
            <h2 class="h5 fw-semibold mb-3">Pedidos actuales</h2>
            <?php if (!empty($pedidosActivos)) : ?>
                <div class="row g-4">
                    <?php foreach ($pedidosActivos as $pedido) :
                        $imagenPedido = $pedido['imagen'] ?: 'https://dummyimage.com/180x180/edf2f7/1a202c&text=Tela';
                    ?>
                        <div class="col-12">
                            <div class="portal-card h-100">
                                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-3">
                                    <div class="flex-shrink-0">
                                        <img src="<?= htmlspecialchars($imagenPedido, ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($pedido['producto'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" class="rounded-4" style="width: 90px; height: 90px; object-fit: cover;">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1">Pedido #<?= (int) $pedido['id']; ?> · <?= htmlspecialchars($pedido['producto'] ?? 'Producto', ENT_QUOTES, 'UTF-8'); ?></h5>
                                        <div class="small text-muted">Creado el <?= htmlspecialchars($pedido['fecha_formateada'], ENT_QUOTES, 'UTF-8'); ?> · <?= count($pedido['detalles'] ?? []); ?> combinaciones</div>
                                    </div>
                                    <div class="text-lg-end">
                                        <span class="status-pill status-<?= htmlspecialchars($pedido['estado'], ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars(ucfirst($pedido['estado']), ENT_QUOTES, 'UTF-8'); ?></span>
                                        <div class="fw-semibold mt-2">Total: Bs <?= htmlspecialchars($pedido['total_formateado'], ENT_QUOTES, 'UTF-8'); ?></div>
                                    </div>
                                </div>
                                <?php if (!empty($pedido['detalles'])) : ?>
                                    <div class="table-responsive mt-3">
                                        <table class="table table-modern table-sm align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Color</th>
                                                    <th>Presentación</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio unitario</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($pedido['detalles'] as $detalle) : ?>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <span class="badge rounded-pill" style="background: <?= htmlspecialchars($detalle['codigo_hex'] ?? '#d1d5db', ENT_QUOTES, 'UTF-8'); ?>; width: 1.5rem; height: 1.5rem;"></span>
                                                                <span><?= htmlspecialchars($detalle['color_legible'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                            </div>
                                                        </td>
                                                        <td class="text-capitalize"><?= htmlspecialchars($detalle['presentacion_legible'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td><?= htmlspecialchars($detalle['cantidad_legible'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td>Bs <?= htmlspecialchars($detalle['precio_unitario_formateado'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td>Bs <?= htmlspecialchars($detalle['subtotal_formateado'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else : ?>
                                    <p class="text-muted mt-3 mb-0">Este pedido aún no registra combinaciones específicas.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="alert alert-info" role="alert">Aún no tienes pedidos activos. Reserva tu próxima tela desde el catálogo.</div>
            <?php endif; ?>
        </div>

        <div class="client-section">
            <h2 class="h5 fw-semibold mb-3">Historial de pedidos</h2>
            <?php if (!empty($pedidosHistoricos)) : ?>
                <div class="row g-4">
                    <?php foreach ($pedidosHistoricos as $pedido) :
                        $imagenPedido = $pedido['imagen'] ?: 'https://dummyimage.com/180x180/edf2f7/1a202c&text=Tela';
                    ?>
                        <div class="col-12">
                            <div class="portal-card h-100">
                                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-3">
                                    <div class="flex-shrink-0">
                                        <img src="<?= htmlspecialchars($imagenPedido, ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($pedido['producto'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" class="rounded-4" style="width: 90px; height: 90px; object-fit: cover;">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1">Pedido #<?= (int) $pedido['id']; ?> · <?= htmlspecialchars($pedido['producto'] ?? 'Producto', ENT_QUOTES, 'UTF-8'); ?></h5>
                                        <div class="small text-muted">Registrado el <?= htmlspecialchars($pedido['fecha_formateada'], ENT_QUOTES, 'UTF-8'); ?> · <?= count($pedido['detalles'] ?? []); ?> combinaciones</div>
                                    </div>
                                    <div class="text-lg-end">
                                        <span class="status-pill status-<?= htmlspecialchars($pedido['estado'], ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars(ucfirst($pedido['estado']), ENT_QUOTES, 'UTF-8'); ?></span>
                                        <div class="fw-semibold mt-2">Total: Bs <?= htmlspecialchars($pedido['total_formateado'], ENT_QUOTES, 'UTF-8'); ?></div>
                                    </div>
                                </div>
                                <?php if (!empty($pedido['detalles'])) : ?>
                                    <div class="table-responsive mt-3">
                                        <table class="table table-modern table-sm align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Color</th>
                                                    <th>Presentación</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio unitario</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($pedido['detalles'] as $detalle) : ?>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <span class="badge rounded-pill" style="background: <?= htmlspecialchars($detalle['codigo_hex'] ?? '#d1d5db', ENT_QUOTES, 'UTF-8'); ?>; width: 1.5rem; height: 1.5rem;"></span>
                                                                <span><?= htmlspecialchars($detalle['color_legible'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                            </div>
                                                        </td>
                                                        <td class="text-capitalize"><?= htmlspecialchars($detalle['presentacion_legible'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td><?= htmlspecialchars($detalle['cantidad_legible'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td>Bs <?= htmlspecialchars($detalle['precio_unitario_formateado'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td>Bs <?= htmlspecialchars($detalle['subtotal_formateado'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else : ?>
                                    <p class="text-muted mt-3 mb-0">Este pedido histórico no registró combinaciones detalladas.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="alert alert-secondary" role="alert">Aquí aparecerá tu historial en cuanto completes tus primeros pedidos.</div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
