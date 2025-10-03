<!-- inventario.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="container mt-4">
    <h2 class="text-center">Gestión de Inventario</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inventario as $item): ?>
            <tr>
                <td><?= $item['nombre'] ?></td>
                <td><?= $item['cantidad'] ?></td>
                <td>
                    <a href="agregar_inventario.php?id=<?= $item['id_producto'] ?>" class="btn btn-info btn-sm">Agregar al Inventario</a>
                    <a href="eliminar_inventario.php?id=<?= $item['id_producto'] ?>" class="btn btn-danger btn-sm">Eliminar del Inventario</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
