<?php
require_once __DIR__ . '/../../models/pedido.php';

$pedidoModel = new Pedido();
$pedidos = $pedidoModel->obtenerTodosLosPedidos();

$pedidos = array_map(function ($pedido) {
    $pedido['total_formateado'] = number_format((float) ($pedido['total'] ?? 0), 2);
    $pedido['fecha_formateada'] = date('d/m/Y H:i', strtotime($pedido['fecha_creacion']));
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
}, $pedidos);
?>
<!-- views/admin/pedidos.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <header class="page-header text-center text-lg-start">
            <h1 class="page-title">Gestión de pedidos</h1>
            <p class="page-subtitle">Sigue el estado de cada solicitud y coordina entregas o reservas con un solo clic.</p>
        </header>
        <?php if (!empty($pedidos)): ?>
            <div class="row g-4">
                <?php foreach ($pedidos as $pedido): ?>
                <?php $imagenPedido = $pedido['imagen'] ?: 'https://dummyimage.com/180x180/f1f5f9/1a202c&text=Tela'; ?>
                <div class="col-12">
                    <div class="portal-card h-100">
                        <div class="d-flex flex-column flex-xl-row gap-3 align-items-xl-center">
                            <div class="flex-shrink-0">
                                <img src="<?= htmlspecialchars($imagenPedido, ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($pedido['producto'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" class="rounded-4" style="width: 96px; height: 96px; object-fit: cover;">
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">Pedido #<?= (int) $pedido['id']; ?> · <?= htmlspecialchars($pedido['producto'] ?? 'Producto', ENT_QUOTES, 'UTF-8'); ?></h5>
                                <div class="d-flex flex-column flex-md-row gap-2 text-muted small">
                                    <span><i class="bi bi-calendar-event"></i> <?= htmlspecialchars($pedido['fecha_formateada'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    <span><i class="bi bi-person"></i> <?= htmlspecialchars($pedido['cliente'] ?? 'Cliente', ENT_QUOTES, 'UTF-8'); ?></span>
                                    <?php if (!empty($pedido['correo_cliente'])): ?>
                                        <span><i class="bi bi-envelope"></i> <?= htmlspecialchars($pedido['correo_cliente'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    <?php endif; ?>
                                    <span><i class="bi bi-collection"></i> <?= count($pedido['detalles'] ?? []); ?> combinaciones</span>
                                </div>
                            </div>
                            <div class="text-xl-end">
                                <span class="status-pill status-<?= htmlspecialchars($pedido['estado'], ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars(ucfirst($pedido['estado']), ENT_QUOTES, 'UTF-8'); ?></span>
                                <div class="fw-semibold mt-2">Total: Bs <?= htmlspecialchars($pedido['total_formateado'], ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>
                        </div>
                        <?php if (!empty($pedido['detalles'])): ?>
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
                                    <?php foreach ($pedido['detalles'] as $detalle): ?>
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
                        <?php else: ?>
                        <p class="text-muted mt-3 mb-0">Sin detalles registrados para este pedido.</p>
                        <?php endif; ?>
                        <div class="d-flex flex-wrap gap-2 justify-content-end mt-3">
                            <button type="button" class="btn btn-success btn-sm" disabled>Confirmar</button>
                            <button type="button" class="btn btn-danger btn-sm" disabled>Cancelar</button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">No hay pedidos registrados en este momento.</div>
        <?php endif; ?>
    </div>
</main>

<?php include('includes/footer.php'); ?>
