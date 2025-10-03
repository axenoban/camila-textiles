<!-- views/cliente/pedidos.php -->
<?php include('../includes/header.php'); ?>
<?php include('../includes/navbar.php'); ?>

<div class="container mt-4">
    <h2 class="text-center">Mis Pedidos</h2>
    
    <h4>Pedidos Actuales</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidosActivos as $pedido): ?>
            <tr>
                <td><?= $pedido['id'] ?></td>
                <td><?= $pedido['producto'] ?></td>
                <td><?= $pedido['cantidad'] ?></td>
                <td><?= $pedido['estado'] ?></td>
                <td>
                    <a href="ver_detalles_pedido.php?id=<?= $pedido['id'] ?>" class="btn btn-info btn-sm">Ver Detalles</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h4>Historial de Pedidos</h4>
    <table class="table table-bordered">
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
            <?php foreach ($pedidosHistoricos as $pedido): ?>
            <tr>
                <td><?= $pedido['id'] ?></td>
                <td><?= $pedido['producto'] ?></td>
                <td><?= $pedido['cantidad'] ?></td>
                <td><?= $pedido['estado'] ?></td>
                <td><?= $pedido['fecha'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>
