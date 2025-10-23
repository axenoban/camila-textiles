<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/../../models/pedido.php';

$pedidoModel = new Pedido();

// 🔹 Usamos pedidos agrupados por producto y fecha
$pedidosCliente = $pedidoModel->obtenerPedidosAgrupadosPorCliente($clienteActual['id']);

$pedidosActivos = array_filter($pedidosCliente, fn($p) => in_array($p['estado'], ['pendiente', 'confirmado'], true));
$pedidosHistoricos = array_filter($pedidosCliente, fn($p) => in_array($p['estado'], ['completado', 'cancelado'], true));

$mensajeReserva = $_SESSION['reserva_mensaje'] ?? null;
$tipoReserva = $_SESSION['reserva_tipo'] ?? null;
unset($_SESSION['reserva_mensaje'], $_SESSION['reserva_tipo']);
?>

<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-area">
    <div class="container-fluid px-4 px-lg-5">
        <section class="client-section text-center text-lg-start mb-4">
            <h1 class="section-heading">Mis pedidos</h1>
            <p class="section-subtitle">Visualiza tus pedidos agrupados por fecha y producto, con todos los colores seleccionados.</p>
        </section>

        <?php if ($mensajeReserva): ?>
            <div class="alert alert-<?= $tipoReserva === 'success' ? 'success' : 'danger'; ?>" role="alert">
                <?= htmlspecialchars($mensajeReserva, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <!-- 🟩 Pedidos activos -->
        <div class="client-section mb-5">
            <h2 class="h5 fw-semibold mb-3">Pedidos actuales</h2>
            <div class="portal-table">
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Colores</th>
                                <th>Unidad</th>
                                <th>Cantidad total</th>
                                <th>Total (Bs)</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pedidosActivos)): ?>
                                <?php foreach ($pedidosActivos as $p): ?>
                                    <?php 
                                        $colores = explode(', ', $p['colores']);
                                        $hex = explode(',', $p['codigos_hex']);
                                        $codigos = explode(',', $p['codigos_color']);
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($p['producto']); ?></td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <?php foreach ($colores as $i => $color): ?>
                                                    <div class="d-flex align-items-center gap-1">
                                                        <span class="color-dot" style="background: <?= htmlspecialchars($hex[$i] ?? '#ccc'); ?>;"></span>
                                                        <small class="fw-semibold"><?= htmlspecialchars($color); ?></small>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>
                                        <td><?= ucfirst($p['unidad']); ?></td>
                                        <td><?= number_format($p['cantidad_total'], 2); ?></td>
                                        <td><strong>Bs <?= number_format($p['total_pedido'], 2, ',', '.'); ?></strong></td>
                                        <td>
                                            <span class="status-pill status-<?= htmlspecialchars($p['estado']); ?>">
                                                <?= ucfirst($p['estado']); ?>
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($p['fecha_pedido'] ?? $p['fecha_creacion'])); ?></td>
                                        <td class="text-end">
                                            <a href="<?= BASE_URL ?>/views/cliente/detalle_pedido.php?id=<?= (int)$p['id_pedido']; ?>"
                                               class="btn btn-outline-primary btn-sm">
                                                Ver detalles
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="8" class="text-center text-muted py-4">No hay pedidos activos actualmente.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 🟦 Historial -->
        <div class="client-section">
            <h2 class="h5 fw-semibold mb-3">Historial de pedidos</h2>
            <div class="portal-table">
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Colores</th>
                                <th>Unidad</th>
                                <th>Cantidad total</th>
                                <th>Total (Bs)</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pedidosHistoricos)): ?>
                                <?php foreach ($pedidosHistoricos as $p): ?>
                                    <?php 
                                        $colores = explode(', ', $p['colores']);
                                        $hex = explode(',', $p['codigos_hex']);
                                        $codigos = explode(',', $p['codigos_color']);
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($p['producto']); ?></td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <?php foreach ($colores as $i => $color): ?>
                                                    <div class="d-flex align-items-center gap-1">
                                                        <span class="color-dot" style="background: <?= htmlspecialchars($hex[$i] ?? '#ccc'); ?>;"></span>
                                                        <small class="fw-semibold"><?= htmlspecialchars($color); ?></small>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>
                                        <td><?= ucfirst($p['unidad']); ?></td>
                                        <td><?= number_format($p['cantidad_total'], 2); ?></td>
                                        <td><strong>Bs <?= number_format($p['total_pedido'], 2, ',', '.'); ?></strong></td>
                                        <td><?= ucfirst($p['estado']); ?></td>
                                        <td><?= date('d/m/Y', strtotime($p['fecha_pedido'] ?? $p['fecha_creacion'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="7" class="text-center text-muted py-4">Aún no tienes pedidos completados o cancelados.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.color-dot {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 1px solid rgba(0,0,0,0.3);
}
.status-pill {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: capitalize;
    color: #fff;
}
.status-pendiente { background: #fbbf24; }
.status-confirmado { background: #3b82f6; }
.status-completado { background: #10b981; }
.status-cancelado { background: #ef4444; }
</style>

<?php include('includes/footer.php'); ?>
