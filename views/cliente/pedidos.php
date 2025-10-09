<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/../../models/pedido.php';

$pedidoModel = new Pedido();
$pedidosCliente = $pedidoModel->obtenerPedidosPorCliente($clienteActual['id']);

$pedidosActivos = array_values(array_filter($pedidosCliente, fn($p) => in_array($p['estado'], ['pendiente', 'confirmado'], true)));

$pedidosHistoricos = array_map(function ($p) {
    $p['fecha_formateada'] = date('d/m/Y', strtotime($p['fecha_creacion']));
    return $p;
}, array_values(array_filter($pedidosCliente, fn($p) => in_array($p['estado'], ['completado', 'cancelado'], true))));

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
            <div class="alert alert-<?= $tipoReserva === 'success' ? 'success' : 'danger'; ?>" role="alert">
                <?= htmlspecialchars($mensajeReserva, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <!-- 🟩 Pedidos activos -->
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
                                        <td><?= htmlspecialchars($pedido['presentacion'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= htmlspecialchars($pedido['color'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= (float) $pedido['cantidad']; ?></td>
                                        <td>$<?= number_format(($pedido['precio_unitario'] ?? 0) * $pedido['cantidad'], 2); ?></td>
                                        <td>
                                            <span class="status-pill status-<?= htmlspecialchars($pedido['estado'], ENT_QUOTES, 'UTF-8'); ?>">
                                                <?= htmlspecialchars(ucfirst($pedido['estado']), ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                        </td>
                                        <td class="text-end text-nowrap">
                                            <a href="#" class="btn btn-info btn-sm disabled" aria-disabled="true">Ver detalles</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        Aún no tienes pedidos activos. Reserva tu próxima tela desde el catálogo.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <div class="alert alert-info" role="alert">Aún no tienes pedidos activos. Reserva tu próxima tela desde el catálogo.</div>
            <?php } ?>
        </div>

        <!-- 🟦 Historial -->
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
                                        <td><?= htmlspecialchars($pedido['presentacion'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= htmlspecialchars($pedido['color'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= (float) $pedido['cantidad']; ?></td>
                                        <td>$<?= number_format($pedido['total'], 2); ?></td>
                                        <td><?= htmlspecialchars(ucfirst($pedido['estado']), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= htmlspecialchars($pedido['fecha_formateada'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        Aquí aparecerá tu historial en cuanto completes tus primeros pedidos.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <div class="alert alert-secondary" role="alert">Aquí aparecerá tu historial en cuanto completes tus primeros pedidos.</div>
            <?php } ?>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
