<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../models/producto.php';

$productoModel = new Producto();
$productoId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$productoId) {
    header('Location: ' . BASE_URL . '/views/admin/productos.php');
    exit;
}

$producto = $productoModel->obtenerProductoPorId($productoId);

if (!$producto) {
    header('Location: ' . BASE_URL . '/views/admin/productos.php?status=no_encontrado');
    exit;
}
?>
<!-- views/admin/editar_producto.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <header class="page-header text-center text-lg-start">
            <h1 class="page-title">Editar producto</h1>
            <p class="page-subtitle">Actualiza la información de la referencia seleccionada para mantener datos confiables en ventas y logística.</p>
        </header>
        <div class="row justify-content-center">
            <div class="col-12 col-xl-8">
                <div class="form-shell">
                    <form action="<?= BASE_URL ?>/controllers/productos.php" method="POST" class="row g-4">
                        <input type="hidden" name="accion" value="actualizar">
                        <input type="hidden" name="id" value="<?= (int) $producto['id']; ?>">
                        <div class="col-12">
                            <label for="nombre" class="form-label">Nombre del producto</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required><?= htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="precio" class="form-label">Precio (USD)</label>
                            <input type="number" class="form-control" id="precio" name="precio" step="0.01" value="<?= htmlspecialchars((string) $producto['precio'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="imagen" class="form-label">URL de imagen</label>
                            <input type="url" class="form-control" id="imagen" name="imagen" value="<?= htmlspecialchars($producto['imagen'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="col-12 text-end">
                            <a href="productos.php" class="btn btn-outline-primary me-2">Volver</a>
                            <button type="submit" class="btn btn-warning">Actualizar producto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
