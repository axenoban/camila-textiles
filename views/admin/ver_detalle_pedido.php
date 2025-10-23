<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../models/pedido.php';

$idPedido = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$idPedido) {
    header('Location: ' . BASE_URL . '/views/admin/pedidos.php');
    exit;
}

$pedidoModel = new Pedido();
$detalles = $pedidoModel->obtenerDetalleAgrupadoAdmin($idPedido);
if (empty($detalles)) {
    header('Location: ' . BASE_URL . '/views/admin/pedidos.php');
    exit;
}

$pedido = $detalles[0];
?>

<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container py-4">
        <a href="<?= BASE_URL ?>/views/admin/pedidos.php" class="btn btn-outline-primary rounded-pill mb-4">
            <i class="bi bi-arrow-left"></i> Volver
        </a>

        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h3 class="fw-bold mb-3">Detalle del Pedido Agrupado</h3>
            <p class="text-muted mb-4">Cliente: <strong><?= htmlspecialchars($pedido['cliente']); ?></strong></p>
            <p><strong>Producto:</strong> <?= htmlspecialchars($pedido['producto']); ?></p>
            <p><strong>Unidad:</strong> <?= ucfirst($pedido['unidad']); ?></p>
            <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($pedido['fecha_creacion'])); ?></p>
            <p><strong>Estado:</strong>
                <span class="badge bg-<?= $pedido['estado'] === 'pendiente' ? 'warning text-dark' : ($pedido['estado'] === 'completado' ? 'success' : 'secondary'); ?>">
                    <?= ucfirst($pedido['estado']); ?>
                </span>
            </p>

            <hr>

            <h5 class="fw-semibold mb-3">Colores y cantidades</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Color</th>
                            <th>Código</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario (Bs)</th>
                            <th>Total (Bs)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detalles as $d): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span style="width:22px;height:22px;border-radius:50%;
            background-color:<?= htmlspecialchars($d['codigo_hex']); ?>;
            border:1px solid #777;"></span>
                                        <span class="fw-semibold"><?= htmlspecialchars($d['codigo_color']); ?></span>
                                        <small class="text-muted"><?= htmlspecialchars($d['nombre_color']); ?></small>
                                    </div>
                                </td>

                                <td><code><?= htmlspecialchars($d['codigo_hex']); ?></code></td>
                                <td><?= number_format($d['cantidad'], 2); ?></td>
                                <td><?= number_format($d['precio_unitario'], 2); ?></td>
                                <td><strong><?= number_format($d['total'], 2); ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-3">
                <h5>Total general: <strong>Bs <?= number_format(array_sum(array_column($detalles, 'total')), 2); ?></strong></h5>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>