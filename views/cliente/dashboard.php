<?php
require_once __DIR__ . '/../../models/pedido.php';
require_once __DIR__ . '/../../models/producto.php';

$pedidoModel = new Pedido();
$productoModel = new Producto();

$pedidosCliente = $pedidoModel->obtenerPedidosPorCliente($clienteActual['id']);
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
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-area">
    <div class="container-fluid px-4 px-lg-5">
        <section class="client-section text-center text-lg-start">
            <h1 class="section-heading">Bienvenido a tu panel</h1>
            <p class="section-subtitle">Consulta pedidos recientes, descubre nuevas telas y actualiza tus datos en minutos.</p>
        </section>
        <div class="row g-4">
            <div class="col-12 col-lg-4">
                <div class="portal-card h-100 text-start">
                    <div class="icon-circle"><i class="bi bi-clock-history"></i></div>
                    <h5 class="fw-semibold">Historial de pedidos</h5>
                    <p class="text-muted">Revisa el estado de tus compras anteriores y descarga comprobantes.</p>
                    <a href="pedidos.php" class="btn btn-outline-primary mt-3">Ver historial</a>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="portal-card h-100 text-start">
                    <div class="icon-circle"><i class="bi bi-gem"></i></div>
                    <h5 class="fw-semibold">Productos disponibles</h5>
                    <p class="text-muted">Explora la colección actualizada con disponibilidad en tiempo real.</p>
                    <a href="productos.php" class="btn btn-success mt-3">Explorar catálogo</a>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="portal-card h-100 text-start">
                    <div class="icon-circle"><i class="bi bi-person-gear"></i></div>
                    <h5 class="fw-semibold">Gestiona tu perfil</h5>
                    <p class="text-muted">Actualiza preferencias de contacto y credenciales de acceso.</p>
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
