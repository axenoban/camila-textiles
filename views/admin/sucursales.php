<!-- sucursales.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="container mt-4">
    <h2 class="text-center">Gestión de Sucursales</h2>
    <a href="agregar_sucursal.php" class="btn btn-success mb-3">Agregar Sucursal</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Horario de Apertura</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sucursales as $sucursal): ?>
            <tr>
                <td><?= $sucursal['id'] ?></td>
                <td><?= $sucursal['nombre'] ?></td>
                <td><?= $sucursal['direccion'] ?></td>
                <td><?= $sucursal['telefono'] ?></td>
                <td><?= $sucursal['horario_apertura'] ?></td>
                <td>
                    <a href="editar_sucursal.php?id=<?= $sucursal['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="eliminar_sucursal.php?id=<?= $sucursal['id'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
