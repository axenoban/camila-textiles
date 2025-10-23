<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../models/producto.php';

$productoModel = new Producto();

$idProducto = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$idProducto) {
    header('Location: ' . BASE_URL . '/views/admin/productos.php?status=error');
    exit;
}

$producto = $productoModel->obtenerProductoPorId($idProducto);
$colores = $productoModel->obtenerColoresPorProducto($idProducto);
?>
<!-- views/admin/colores_producto.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">

        <!-- ENCABEZADO -->
        <header class="page-header text-center text-lg-start mb-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h1 class="page-title mb-2">Gestión de colores</h1>
                    <p class="page-subtitle text-muted mb-0">
                        Administra los códigos, muestras y stock de colores asociados al producto seleccionado.
                    </p>
                </div>
                <a href="productos.php" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </header>

        <!-- PRODUCTO SELECCIONADO -->
        <div class="portal-card mb-4 d-flex align-items-center gap-3">
            <?php if (filter_var($producto['imagen_principal'], FILTER_VALIDATE_URL)): ?>
                <!-- Si es una URL, mostrarla directamente -->
                <img src="<?= htmlspecialchars($producto['imagen_principal']); ?>"
                     alt="Imagen producto"
                     style="width:90px;height:90px;border-radius:10px;object-fit:cover;box-shadow:0 0 6px rgba(0,0,0,0.1);">
            <?php else: ?>
                <!-- Si es una ruta local, mostrarla desde el directorio de uploads -->
                <img src="<?= BASE_URL . '/uploads/' . basename($producto['imagen_principal']); ?>"
                     alt="Imagen producto"
                     style="width:90px;height:90px;border-radius:10px;object-fit:cover;box-shadow:0 0 6px rgba(0,0,0,0.1);">
            <?php endif; ?>
            <div>
                <h5 class="fw-semibold mb-1"><?= htmlspecialchars($producto['nombre']); ?></h5>
                <p class="text-muted small mb-0"><?= htmlspecialchars($producto['descripcion']); ?></p>
            </div>
        </div>

        <!-- 🟢 FORMULARIO PARA NUEVO COLOR -->
        <div class="portal-form mb-5">
            <h5 class="fw-semibold mb-3"><i class="bi bi-plus-circle me-2"></i>Registrar nuevo color</h5>
            <form action="<?= BASE_URL ?>/controllers/colores.php" method="POST" class="row g-4">
                <input type="hidden" name="accion" value="crear">
                <input type="hidden" name="id_producto" value="<?= (int)$idProducto; ?>">

                <div class="col-md-4">
                    <label for="nombre_color" class="form-label">Nombre del color</label>
                    <input type="text" class="form-control" id="nombre_color" name="nombre_color" required>
                </div>

                <div class="col-md-4">
                    <label for="codigo_color" class="form-label">Código interno</label>
                    <input type="number" class="form-control" id="codigo_color" name="codigo_color" min="1" max="999" required>
                    <small class="text-muted">Debe ser único dentro del producto</small>
                </div>

                <div class="col-md-4">
                    <label for="codigo_hex" class="form-label">Color (muestra visual)</label>
                    <input type="color" class="form-control form-control-color" id="codigo_hex" name="codigo_hex" value="#ffffff" title="Seleccionar color">
                </div>

                <div class="col-md-6">
                    <label for="stock_metros" class="form-label">Stock (metros)</label>
                    <input type="number" step="0.01" class="form-control" id="stock_metros" name="stock_metros" value="0" required>
                </div>

                <div class="col-md-6">
                    <label for="stock_rollos" class="form-label">Stock (rollos)</label>
                    <input type="number" step="0.01" class="form-control" id="stock_rollos" name="stock_rollos" value="0" required>
                </div>

                <div class="col-md-8">
                    <label for="imagen_muestra" class="form-label">URL de imagen muestra</label>
                    <input type="url" class="form-control" id="imagen_muestra" name="imagen_muestra" placeholder="https://...">
                </div>

                <div class="col-md-4">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado">
                        <option value="disponible" selected>Disponible</option>
                        <option value="agotado">Agotado</option>
                    </select>
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Guardar color
                    </button>
                </div>
            </form>
        </div>

        <!-- 🧾 TABLA DE COLORES REGISTRADOS -->
        <div class="portal-table">
            <h5 class="fw-semibold mb-3"><i class="bi bi-palette me-2"></i>Colores registrados</h5>
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Muestra</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($colores)): ?>
                            <?php foreach ($colores as $color): ?>
                                <tr>
                                    <td><?= (int)$color['id']; ?></td>
                                    <td>
                                        <div style="width:28px;height:28px;border-radius:50%;
                                                    background-color:<?= htmlspecialchars($color['codigo_hex']); ?>;
                                                    border:1px solid #aaa;box-shadow:0 0 3px rgba(0,0,0,0.1);">
                                        </div>
                                    </td>
                                    <td><strong><?= htmlspecialchars($color['codigo_color']); ?></strong></td>
                                    <td><?= htmlspecialchars($color['nombre_color']); ?></td>
                                    <td>
                                        <small>M: <?= number_format($color['stock_metros'], 2); ?></small><br>
                                        <small>R: <?= number_format($color['stock_rollos'], 2); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge <?= $color['estado'] === 'disponible' ? 'bg-success' : 'bg-secondary'; ?>">
                                            <?= ucfirst($color['estado']); ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?= BASE_URL ?>/controllers/colores.php?accion=eliminar&id=<?= (int)$color['id']; ?>&id_producto=<?= (int)$idProducto; ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('¿Eliminar este color del producto?');">
                                           <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No hay colores registrados para este producto.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<style>
.portal-form, .portal-card, .portal-table {
    background: #fff;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.portal-form h5, .portal-table h5 {
    color: #0d6efd;
}
.table-modern th {
    font-weight: 600;
    background: #f8fafc;
}
</style>

<?php include('includes/footer.php'); ?>
