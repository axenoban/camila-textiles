<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/../../models/pedido.php';

// ✅ Evitar error de sesión duplicada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$idPedido = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$idUsuario = $_SESSION['usuario']['id'] ?? null;

if (!$idPedido || !$idUsuario) {
    header('Location: ' . BASE_URL . '/views/cliente/pedidos.php');
    exit;
}

$pedidoModel = new Pedido();
$pedido = $pedidoModel->obtenerDetalleAgrupado($idUsuario, $idPedido);

if (!$pedido) {
    header('Location: ' . BASE_URL . '/views/cliente/pedidos.php');
    exit;
}
?>

<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-content py-5">
    <div class="container">
        <div class="portal-card mx-auto" style="max-width:740px;">
            <a href="<?= BASE_URL ?>/views/cliente/pedidos.php" class="btn btn-outline-primary rounded-pill mb-4">
                <i class="bi bi-arrow-left"></i> Volver a mis pedidos
            </a>

            <h2 class="fw-semibold mb-2">Detalle del pedido #<?= (int)$pedido['id_pedido']; ?></h2>
            <p class="text-muted mb-4">Revisa los colores y detalles de tu pedido agrupado.</p>

            <!-- Información principal -->
            <div class="pedido-info">
                <p><strong>Producto:</strong> <?= htmlspecialchars($pedido['producto']); ?></p>

                <p><strong>Colores seleccionados:</strong></p>
                <div class="d-flex flex-wrap gap-2 my-3">
                    <?php
                    $colores = explode(', ', $pedido['colores']);
                    $hex = explode(',', $pedido['codigos_hex']);
                    $codigos = explode(',', $pedido['codigos_color'] ?? '');
                    foreach ($colores as $i => $color):
                    ?>
                        <div class="color-item d-flex align-items-center gap-2 px-2 py-1 border rounded-pill bg-light shadow-sm">
                            <span class="color-dot" style="background-color:<?= htmlspecialchars($hex[$i] ?? '#ccc'); ?>;"></span>
                            <span class="fw-semibold"><?= htmlspecialchars($codigos[$i] ?? '—'); ?></span>
                            <small class="text-muted"><?= htmlspecialchars($color); ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>

                <hr>

                <p><strong>Unidad:</strong> <?= ucfirst(htmlspecialchars($pedido['unidad'])); ?></p>
                <p><strong>Cantidad total:</strong> <?= number_format($pedido['cantidad_total'], 2); ?></p>
                <p><strong>Precio unitario:</strong> Bs <?= number_format($pedido['precio_unitario'], 2, ',', '.'); ?></p>
                <p><strong>Total del pedido:</strong> <span class="fw-bold text-success">Bs <?= number_format($pedido['total_pedido'], 2, ',', '.'); ?></span></p>

                <p>
                    <strong>Estado:</strong>
                    <span class="badge 
                        <?= match ($pedido['estado']) {
                            'pendiente' => 'bg-warning text-dark',
                            'confirmado' => 'bg-primary',
                            'completado' => 'bg-success',
                            'cancelado' => 'bg-secondary',
                            default => 'bg-light text-dark'
                        }; ?>">
                        <?= ucfirst($pedido['estado']); ?>
                    </span>
                </p>

                <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($pedido['fecha_creacion'])); ?></p>
            </div>
        </div>
    </div>
</main>

<style>
.portal-card {
    background: #fff;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}
.color-item {
    transition: all 0.2s ease-in-out;
}
.color-item:hover {
    background: #f1f1f1;
    transform: scale(1.02);
}
.color-dot {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 1px solid rgba(0,0,0,0.3);
}
.badge {
    font-size: 0.85rem;
    padding: 0.4rem 0.75rem;
}
</style>

<?php include('includes/footer.php'); ?>
