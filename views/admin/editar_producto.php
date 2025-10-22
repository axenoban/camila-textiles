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
        <header class="page-header text-center text-lg-start mb-4">
            <h1 class="page-title mb-2">Editar producto</h1>
            <p class="page-subtitle text-muted">
                Actualiza los datos técnicos, precios y disponibilidad del producto seleccionado.
            </p>
        </header>

        <div class="row justify-content-center">
            <div class="col-12 col-xl-8">
                <div class="form-shell">
                    <form action="<?= BASE_URL ?>/controllers/productos.php" method="POST" enctype="multipart/form-data" class="row g-4">
                        <input type="hidden" name="accion" value="actualizar">
                        <input type="hidden" name="id" value="<?= (int) $producto['id']; ?>">

                        <!-- 🏷 Nombre y descripción -->
                        <div class="col-12">
                            <label for="nombre" class="form-label fw-semibold">Nombre del producto</label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                value="<?= htmlspecialchars($producto['nombre']); ?>" required>
                        </div>

                        <div class="col-12">
                            <label for="descripcion" class="form-label fw-semibold">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required><?= htmlspecialchars($producto['descripcion']); ?></textarea>
                        </div>

                        <!-- 🧵 Detalles técnicos -->
                        <div class="col-md-6">
                            <label for="tipo_tela" class="form-label">Tipo de tela</label>
                            <input type="text" class="form-control" id="tipo_tela" name="tipo_tela"
                                value="<?= htmlspecialchars($producto['tipo_tela'] ?? ''); ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="composicion" class="form-label">Composición</label>
                            <input type="text" class="form-control" id="composicion" name="composicion"
                                value="<?= htmlspecialchars($producto['composicion'] ?? ''); ?>" required>
                        </div>

                        <!-- 💰 Precios -->
                        <div class="col-md-6">
                            <label for="precio_metro" class="form-label">Precio por metro (Bs)</label>
                            <input type="number" step="0.01" class="form-control" id="precio_metro" name="precio_metro"
                                value="<?= htmlspecialchars($producto['precio_metro']); ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="precio_rollo" class="form-label">Precio por rollo (Bs)</label>
                            <input type="number" step="0.01" class="form-control" id="precio_rollo" name="precio_rollo"
                                value="<?= htmlspecialchars($producto['precio_rollo']); ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="ancho_metros" class="form-label">Ancho (m)</label>
                            <input type="number" step="0.01" class="form-control" id="ancho_metros" name="ancho_metros"
                                value="<?= htmlspecialchars($producto['ancho_metros']); ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="metros_por_rollo" class="form-label">Metros por rollo (aproximado)</label>
                            <input type="number" step="0.01" class="form-control" id="metros_por_rollo" name="metros_por_rollo"
                                value="<?= htmlspecialchars($producto['metros_por_rollo']); ?>" required>
                        </div>

                        <!-- 🖼 Imagen principal -->
                        <div class="col-12">
                            <label for="imagen_local" class="form-label">Cambiar imagen principal</label>
                            <input type="file" class="form-control" id="imagen_local" name="imagen_local">
                            <div class="mt-3">
                                <!-- Mostrar la imagen actual si existe -->
                                <?php if ($producto['imagen_principal']): ?>
                                    <img src="<?= BASE_URL . '/uploads/' . basename($producto['imagen_principal']); ?>"
                                         alt="Vista previa"
                                         style="max-width:150px;border-radius:10px;box-shadow:0 0 5px rgba(0,0,0,0.2);">
                                    <!-- Pasamos la imagen existente como valor al campo oculto -->
                                    <input type="hidden" name="imagen_principal" value="<?= $producto['imagen_principal']; ?>">
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- 👁 Visibilidad -->
                        <div class="col-md-6">
                            <label for="visible" class="form-label">Visibilidad pública</label>
                            <select class="form-select" id="visible" name="visible">
                                <option value="1" <?= $producto['visible'] ? 'selected' : ''; ?>>Visible</option>
                                <option value="0" <?= !$producto['visible'] ? 'selected' : ''; ?>>Oculto</option>
                            </select>
                        </div>

                        <!-- 🎯 Botones -->
                        <div class="col-12 text-end">
                            <a href="productos.php" class="btn btn-outline-primary me-2">
                                <i class="bi bi-arrow-left"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save"></i> Guardar cambios
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
