<?php
include('includes/header.php');
require_once __DIR__ . '/../../models/pedido.php';

$pedidoModel = new Pedido();
$clienteId = $clienteActual['id'] ?? null;
$pedidosCliente = $clienteId ? $pedidoModel->obtenerPedidosPorCliente($clienteId) : [];
$pedidosActivos = array_values(array_filter($pedidosCliente, function ($pedido) {
    return in_array($pedido['estado'], ['pendiente', 'confirmado']);
}));
$pedidosHistoricos = array_values(array_filter($pedidosCliente, function ($pedido) {
    return in_array($pedido['estado'], ['completado', 'cancelado']);
}));
?>
<!-- views/cliente/pedidos.php -->
<?php include('includes/navbar.php'); ?>

<main class="main-area">
    <div class="container-fluid px-4 px-lg-5">
        <section class="client-section text-center text-lg-start">
            <h1 class="section-heading">Mis pedidos</h1>
            <p class="section-subtitle">Supervisa tus pedidos activos y consulta tu historial en un panel claro y accesible.</p>
        </section>
        <div class="client-section">
            <h2 class="h5 fw-semibold mb-3">Pedidos actuales</h2>
            <div class="portal-table">
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID Pedido</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Estado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pedidosActivos)): ?>
                                <?php foreach ($pedidosActivos as $pedido): ?>
                                    <tr>
                                        <td>#<?= $pedido['id'] ?></td>
                                        <td><?= htmlspecialchars($pedido['producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= $pedido['cantidad'] ?></td>
                                        <td>
                                            <span class="status-pill status-<?= htmlspecialchars($pedido['estado'], ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars(ucfirst($pedido['estado']), ENT_QUOTES, 'UTF-8'); ?></span>
                                        </td>
                                        <td class="text-end text-nowrap">
                                            <a href="ver_detalles_pedido.php?id=<?= $pedido['id'] ?>" class="btn btn-info btn-sm">Ver detalles</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No tienes pedidos activos por el momento.</td>
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
                                <th>Cantidad</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pedidosHistoricos)): ?>
                                <?php foreach ($pedidosHistoricos as $pedido): ?>
                                    <tr>
                                        <td>#<?= $pedido['id'] ?></td>
                                        <td><?= htmlspecialchars($pedido['producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= $pedido['cantidad'] ?></td>
                                        <td>
                                            <span class="status-pill status-<?= htmlspecialchars($pedido['estado'], ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars(ucfirst($pedido['estado']), ENT_QUOTES, 'UTF-8'); ?></span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($pedido['fecha_creacion'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Aún no registras pedidos finalizados.</td>
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
