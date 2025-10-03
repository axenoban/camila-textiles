<!-- empleados.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="container mt-4">
    <h2 class="text-center">Gestión de Empleados</h2>
    <a href="agregar_empleado.php" class="btn btn-success mb-3">Agregar Empleado</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Puesto</th>
                <th>Salario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($empleados as $empleado): ?>
            <tr>
                <td><?= $empleado['id'] ?></td>
                <td><?= $empleado['nombre'] ?></td>
                <td><?= $empleado['puesto'] ?></td>
                <td><?= '$' . number_format($empleado['salario'], 2) ?></td>
                <td>
                    <a href="editar_empleado.php?id=<?= $empleado['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="eliminar_empleado.php?id=<?= $empleado['id'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
