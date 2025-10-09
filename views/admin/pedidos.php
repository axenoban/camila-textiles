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
                            <th>Unidad</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                            <?php
                                $cliente = htmlspecialchars($pedido['cliente'] ?? '', ENT_QUOTES, 'UTF-8');
                                $producto = htmlspecialchars($pedido['producto'] ?? '', ENT_QUOTES, 'UTF-8');
                                $estado = htmlspecialchars($pedido['estado'] ?? '', ENT_QUOTES, 'UTF-8');
                                $unidad = isset($pedido['unidad_venta']) && $pedido['unidad_venta'] === 'rollo' ? 'Rollo' : 'Metro';
                            ?>
                            <tr>
                                <td><?= $pedido['id'] ?></td>
                                <td><?= $cliente ?></td>
                                <td><?= $producto ?></td>
                                <td><?= number_format($pedido['cantidad'], 2, ',', '.') ?></td>
                                <td><?= $unidad ?></td>
                                <td><?= $estado ?></td>
                                <td class="text-end text-nowrap">
                                    <a href="confirmar_pedido.php?id=<?= $pedido['id'] ?>" class="btn btn-success btn-sm me-2">Confirmar</a>
                                    <a href="cancelar_pedido.php?id=<?= $pedido['id'] ?>" class="btn btn-danger btn-sm">Cancelar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
