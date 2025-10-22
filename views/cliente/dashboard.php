<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/../../models/pedido.php';
require_once __DIR__ . '/../../models/producto.php';

$pedidoModel = new Pedido();
$productoModel = new Producto();

$clienteActual = $_SESSION['usuario']; // sesión activa

// 🔹 Pedidos agrupados por producto + día
$pedidosCliente = $pedidoModel->obtenerPedidosAgrupadosPorCliente($clienteActual['id']);

$pedidosActivos = array_filter($pedidosCliente, fn($p) => in_array($p['estado'], ['pendiente', 'confirmado']));
$pedidosCompletados = array_filter($pedidosCliente, fn($p) => $p['estado'] === 'completado');

$totalPedidos = count($pedidosCliente);
$totalActivos = count($pedidosActivos);
$totalCompletados = count($pedidosCompletados);
$productosDisponibles = $productoModel->contarProductosVisibles();

// 💰 Calcular gasto total real
$totalGasto = array_reduce($pedidosCliente, function ($carry, $pedido) {
    return $carry + (float)($pedido['total_pedido'] ?? 0);
}, 0);

// 📅 Mostrar los 4 pedidos más recientes
usort($pedidosCliente, fn($a, $b) => strtotime($b['fecha_creacion']) <=> strtotime($a['fecha_creacion']));
$pedidosRecientes = array_slice($pedidosCliente, 0, 4);
?>

<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-area">
    <div class="container-fluid px-4 px-lg-5">

        <!-- 👋 Bienvenida -->
        <section class="client-section text-center text-lg-start mb-5">
            <h1 class="section-heading mb-2">¡Hola, <?= htmlspecialchars($clienteActual['nombre']); ?>!</h1>
            <p class="section-subtitle">
                Desde tu panel puedes revisar tus pedidos, explorar nuevos productos y mantener actualizado tu perfil.
            </p>
        </section>

        <!-- 📊 Tarjetas de resumen -->
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="portal-card text-center">
                    <div class="icon-circle"><i class="bi bi-bag-check"></i></div>
                    <h5 class="fw-semibold mb-1"><?= $totalPedidos ?></h5>
                    <p class="text-muted small mb-0">Pedidos totales</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="portal-card text-center">
                    <div class="icon-circle"><i class="bi bi-hourglass-split"></i></div>
                    <h5 class="fw-semibold mb-1"><?= $totalActivos ?></h5>
                    <p class="text-muted small mb-0">Pedidos activos</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="portal-card text-center">
                    <div class="icon-circle"><i class="bi bi-check2-circle"></i></div>
                    <h5 class="fw-semibold mb-1"><?= $totalCompletados ?></h5>
                    <p class="text-muted small mb-0">Completados</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="portal-card text-center">
                    <div class="icon-circle"><i class="bi bi-currency-dollar"></i></div>
                    <h5 class="fw-semibold mb-1">Bs <?= number_format($totalGasto, 2, ',', '.') ?></h5>
                    <p class="text-muted small mb-0">Gasto total</p>
                </div>
            </div>
        </div>

        <!-- 🧭 Accesos rápidos -->
        <div class="row g-4 mb-5">
            <div class="col-12 col-lg-4">
                <div class="portal-card h-100 text-start">
                    <div class="icon-circle"><i class="bi bi-clock-history"></i></div>
                    <h5 class="fw-semibold">Historial de pedidos</h5>
                    <p class="text-muted">Consulta tus compras anteriores y su estado actual.</p>
                    <a href="pedidos.php" class="btn btn-outline-primary mt-3">Ver historial</a>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="portal-card h-100 text-start">
                    <div class="icon-circle"><i class="bi bi-gem"></i></div>
                    <h5 class="fw-semibold">Explorar productos</h5>
                    <p class="text-muted">Descubre nuevas telas con disponibilidad inmediata.</p>
                    <a href="productos.php" class="btn btn-success mt-3">Ver catálogo</a>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="portal-card h-100 text-start">
                    <div class="icon-circle"><i class="bi bi-person-gear"></i></div>
                    <h5 class="fw-semibold">Gestionar perfil</h5>
                    <p class="text-muted">Actualiza tus datos personales y credenciales.</p>
                    <a href="perfil.php" class="btn btn-info mt-3">Editar perfil</a>
                </div>
            </div>
        </div>

        <!-- 🕓 Actividad reciente -->
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
                                    <th>Colores</th>
                                    <th>Cantidad total</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pedidosRecientes as $pedido): ?>
                                    <?php 
                                        $colores = explode(', ', $pedido['colores']);
                                        $hex = explode(',', $pedido['codigos_hex']);
                                    ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($pedido['fecha_pedido'] ?? $pedido['fecha_creacion'])); ?></td>
                                        <td><?= htmlspecialchars($pedido['producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <?php foreach ($colores as $i => $color): ?>
                                                    <span class="d-inline-flex align-items-center gap-1">
                                                        <span class="color-chip" style="background: <?= htmlspecialchars($hex[$i] ?? '#ccc'); ?>;"></span>
                                                        <small><?= htmlspecialchars($color); ?></small>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>
                                        <td><?= number_format($pedido['cantidad_total'], 2, ',', '.'); ?></td>
                                        <td>
                                            <span class="status-pill status-<?= htmlspecialchars($pedido['estado']); ?>">
                                                <?= ucfirst($pedido['estado']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <h6 class="fw-semibold text-muted mb-0">Aún no has realizado pedidos</h6>
                <p class="text-muted small">Explora productos y realiza tu primera reserva.</p>
                <a href="productos.php" class="btn btn-primary rounded-pill px-4">Explorar catálogo</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
.portal-card {
    background: #fff;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 3px 12px rgba(0,0,0,0.05);
    transition: all .25s ease;
}
.portal-card:hover { transform: translateY(-3px); }
.icon-circle {
    width: 50px; height: 50px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    background: rgba(13,110,253,0.1);
    color: #0d6efd;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}
.color-chip {
    width: 16px; height: 16px;
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
