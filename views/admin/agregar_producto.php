<!-- agregar_producto.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="container mt-4">
    <h2 class="text-center">Agregar Producto</h2>
    <form action="productos.php" method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Producto</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen URL</label>
            <input type="url" class="form-control" id="imagen" name="imagen" required>
        </div>
        <button type="submit" class="btn btn-success">Agregar Producto</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
