<!-- agregar_sucursal.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="container mt-4">
    <h2 class="text-center">Agregar Sucursal</h2>
    <form action="sucursales.php" method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Sucursal</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" required>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" required>
        </div>
        <div class="mb-3">
            <label for="horario_apertura" class="form-label">Horario de Apertura</label>
            <input type="text" class="form-control" id="horario_apertura" name="horario_apertura" required>
        </div>
        <button type="submit" class="btn btn-success">Agregar Sucursal</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
