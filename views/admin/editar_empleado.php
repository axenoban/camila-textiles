<!-- editar_empleado.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="container mt-4">
    <h2 class="text-center">Editar Empleado</h2>
    <form action="empleados.php" method="POST">
        <input type="hidden" name="id" value="<?= $empleado['id'] ?>">

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Empleado</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $empleado['nombre'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="puesto" class="form-label">Puesto</label>
            <input type="text" class="form-control" id="puesto" name="puesto" value="<?= $empleado['puesto'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="salario" class="form-label">Salario</label>
            <input type="number" class="form-control" id="salario" name="salario" step="0.01" value="<?= $empleado['salario'] ?>" required>
        </div>
        <button type="submit" class="btn btn-warning">Actualizar Empleado</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
