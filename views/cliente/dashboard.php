<?php
include('includes/header.php');
require_once __DIR__ . '/../../models/pedido.php';
require_once __DIR__ . '/../../models/producto.php';

$pedidoModel = new Pedido();
$productoModel = new Producto();

$clienteId = $clienteActual['id'] ?? null;
$pedidosCliente = $clienteId ? $pedidoModel->obtenerPedidosPorCliente($clienteId) : [];
$pedidosActivos = array_filter($pedidosCliente, function ($pedido) {
    return in_array($pedido['estado'], ['pendiente', 'confirmado']);
});
$pedidosCompletados = array_filter($pedidosCliente, function ($pedido) {
    return $pedido['estado'] === 'completado';
});
$totalPedidos = count($pedidosCliente);
$productosDisponibles = $productoModel->contarProductosVisibles();
$totalGasto = array_reduce($pedidosCliente, function ($carry, $pedido) {
    return $carry + ($pedido['precio'] * $pedido['cantidad']);
}, 0);
$pedidosRecientes = array_slice($pedidosCliente, 0, 3);
$ultimoPedido = $pedidosCliente[0] ?? null;
?>
<!-- views/cliente/dashboard.php -->
<?php include('includes/navbar.php'); ?>

<main class="main-area">
    <div class="container-fluid px-4 px-lg-5">
        <section class="client-section text-center text-lg-start">
            <h1 class="section-heading">Hola, <?= htmlspecialchars($clienteActual['nombre'], ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="section-subtitle">Tienes <?= count($pedidosActivos); ?> pedidos activos y <?= count($pedidosCompletados); ?> completados. Has invertido $<?= number_format($totalGasto, 2); ?> en Camila Textil.</p>
        </section>
        <div class="row g-4">
            <div class="col-12 col-lg-4">
                <div class="portal-card h-100 text-start">
                    <div class="icon-circle"><i class="bi bi-clock-history"></i></div>
                    <h5 class="fw-semibold">Historial de pedidos</h5>
                    <p class="text-muted">Has gestionado <?= $totalPedidos; ?> pedidos desde tu cuenta.</p>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <span class="client-pill warning"><?= count($pedidosActivos); ?> en curso</span>
                        <span class="client-pill success"><?= count($pedidosCompletados); ?> completados</span>
                    </div>
                    <a href="pedidos.php" class="btn btn-outline-primary mt-3">Ver historial</a>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="portal-card h-100 text-start">
                    <div class="icon-circle"><i class="bi bi-gem"></i></div>
                    <h5 class="fw-semibold">Productos disponibles</h5>
                    <p class="text-muted">Explora <?= $productosDisponibles; ?> telas con stock garantizado.</p>
                    <a href="productos.php" class="btn btn-success mt-3">Explorar catálogo</a>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="portal-card h-100 text-start">
                    <div class="icon-circle"><i class="bi bi-person-gear"></i></div>
                    <h5 class="fw-semibold">Gestiona tu perfil</h5>
                    <p class="text-muted">Mantén tus datos al día y controla tus preferencias de contacto.</p>
                    <?php if ($ultimoPedido): ?>
                        <div class="d-flex flex-column small text-muted mt-3">
                            <span>Último pedido: <?= date('d/m/Y', strtotime($ultimoPedido['fecha_creacion'])); ?></span>
                            <span>Estado: <?= htmlspecialchars(ucfirst($ultimoPedido['estado']), ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                    <?php endif; ?>
                    <a href="perfil.php" class="btn btn-info mt-3">Configurar perfil</a>
                </div>
            </div>
        </div>
        <?php if (!empty($pedidosRecientes)): ?>
            <div class="client-section">
                <h2 class="h5 fw-semibold mb-3">Actividad reciente</h2>
                <div class="portal-table">
                    <div class="table-responsive">
                        <table class="table table-modern align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pedidosRecientes as $pedido): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($pedido['fecha_creacion'])); ?></td>
                                        <td><?= htmlspecialchars($pedido['producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= $pedido['cantidad']; ?></td>
                                        <td>
                                            <span class="status-pill status-<?= htmlspecialchars($pedido['estado'], ENT_QUOTES, 'UTF-8'); ?>">
                                                <?= htmlspecialchars(ucfirst($pedido['estado']), ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include('includes/footer.php'); ?>
