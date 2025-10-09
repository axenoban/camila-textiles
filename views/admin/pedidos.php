<?php
require_once __DIR__ . '/../../models/pedido.php';

$pedidoModel = new Pedido();
$pedidos = $pedidoModel->obtenerTodosLosPedidos();
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
        <div class="table-shell">
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Cliente</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pedidos)): ?>
                            <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td><?= (int) $pedido['id']; ?></td>
                                <td><?= htmlspecialchars($pedido['cliente'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($pedido['producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= (int) $pedido['cantidad']; ?></td>
                                <td>
                                    <span class="status-pill status-<?= htmlspecialchars($pedido['estado'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <?= htmlspecialchars(ucfirst($pedido['estado']), ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="confirmar_pedido.php?id=<?= (int) $pedido['id']; ?>" class="btn btn-success btn-sm disabled" aria-disabled="true">Confirmar</a>
                                    <a href="cancelar_pedido.php?id=<?= (int) $pedido['id']; ?>" class="btn btn-danger btn-sm disabled" aria-disabled="true">Cancelar</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No hay pedidos registrados en este momento.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
