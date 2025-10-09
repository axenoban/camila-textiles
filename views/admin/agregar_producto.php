<!-- views/admin/agregar_producto.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <header class="page-header text-center text-lg-start">
            <h1 class="page-title">Agregar nuevo producto</h1>
            <p class="page-subtitle">Registra textiles con su ficha técnica, precio y fotografía para mantener el catálogo actualizado.</p>
        </header>
        <div class="row justify-content-center">
            <div class="col-12 col-xl-8">
                <div class="form-shell">
                    <form action="productos.php" method="POST" class="row g-4">
                        <div class="col-12">
                            <label for="nombre" class="form-label">Nombre del producto</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="color" class="form-label">Color predominante</label>
                            <input type="text" class="form-control" id="color" name="color" placeholder="Ej. Azul petróleo" required>
                        </div>
                        <div class="col-md-6">
                            <label for="unidad_venta" class="form-label">Unidad de venta</label>
                            <select class="form-select" id="unidad_venta" name="unidad_venta" required>
                                <option value="metro">Metro lineal</option>
                                <option value="rollo">Rollo completo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="precio" class="form-label">Precio (Bs)</label>
                            <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label for="imagen" class="form-label">URL de imagen</label>
                            <input type="url" class="form-control" id="imagen" name="imagen" required>
                        </div>
                        <div class="col-12 text-end">
                            <a href="productos.php" class="btn btn-outline-primary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-success">Guardar producto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
