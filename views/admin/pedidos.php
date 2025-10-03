<!-- pedidos.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="container mt-4">
    <h2 class="text-center">Gestión de Pedidos</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Cliente</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td><?= $pedido['id'] ?></td>
                <td><?= $pedido['cliente'] ?></td>
                <td><?= $pedido['producto'] ?></td>
                <td><?= $pedido['cantidad'] ?></td>
                <td><?= $pedido['estado'] ?></td>
                <td>
                    <a href="confirmar_pedido.php?id=<?= $pedido['id'] ?>" class="btn btn-success btn-sm">Confirmar</a>
                    <a href="cancelar_pedido.php?id=<?= $pedido['id'] ?>" class="btn btn-danger btn-sm">Cancelar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
