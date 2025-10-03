<!-- agregar_empleado.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="container mt-4">
    <h2 class="text-center">Agregar Empleado</h2>
    <form action="empleados.php" method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Empleado</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="puesto" class="form-label">Puesto</label>
            <input type="text" class="form-control" id="puesto" name="puesto" required>
        </div>
        <div class="mb-3">
            <label for="salario" class="form-label">Salario</label>
            <input type="number" class="form-control" id="salario" name="salario" step="0.01" required>
        </div>
        <button type="submit" class="btn btn-success">Agregar Empleado</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
