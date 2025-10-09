<?php
require_once __DIR__ . '/../../models/producto.php';

$productoModel = new Producto();
$productos = $productoModel->obtenerTodosLosProductos();
?>
<!-- views/admin/productos.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <?php
        $status = $_GET['status'] ?? null;
        $mensajes = [
            'creado' => ['type' => 'success', 'text' => 'El producto se añadió al catálogo.'],
            'actualizado' => ['type' => 'success', 'text' => 'Los datos del producto se guardaron correctamente.'],
            'eliminado' => ['type' => 'success', 'text' => 'El producto se eliminó del catálogo.'],
            'visibilidad_on' => ['type' => 'success', 'text' => 'El producto ahora es visible para clientes y visitantes.'],
            'visibilidad_off' => ['type' => 'info', 'text' => 'El producto se ocultó del catálogo público.'],
            'no_encontrado' => ['type' => 'warning', 'text' => 'El producto solicitado no existe.'],
            'error' => ['type' => 'danger', 'text' => 'No se pudo completar la operación solicitada.'],
        ];
        ?>
        <?php if ($status && isset($mensajes[$status])): ?>
        <div class="alert alert-<?= $mensajes[$status]['type']; ?> alert-dismissible fade show" role="alert" data-auto-dismiss="true">
            <?= htmlspecialchars($mensajes[$status]['text'], ENT_QUOTES, 'UTF-8'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
        <?php endif; ?>
        <header class="page-header text-center text-lg-start">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h1 class="page-title mb-2">Gestión de productos</h1>
                    <p class="page-subtitle mb-0">Controla el catálogo de telas, su disponibilidad y precios para asegurar una experiencia de compra impecable.</p>
                </div>
                <a href="agregar_producto.php" class="btn btn-success">Agregar producto</a>
            </div>
        </header>
        <?php if (!empty($productos)): ?>
            <div class="row g-4">
                <?php foreach ($productos as $producto): ?>
                <?php $visible = (bool) ($producto['visible'] ?? false); ?>
                <div class="col-12 col-xl-6">
                    <div class="portal-card h-100">
                        <div class="d-flex gap-3 flex-column flex-lg-row">
                            <div class="flex-shrink-0">
                                <img src="<?= htmlspecialchars($producto['imagen'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>" class="rounded-4" style="width: 160px; height: 160px; object-fit: cover;">
                            </div>
                            <div class="flex-grow-1 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                    <div>
                                        <h5 class="fw-semibold mb-1"><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?></h5>
                                        <p class="text-muted mb-2"><?= htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    </div>
                                    <span class="badge <?= $visible ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-dark'; ?>">
                                        <?= $visible ? 'Publicado' : 'Oculto'; ?>
                                    </span>
                                </div>
                                <div class="row g-3 small text-muted">
                                    <div class="col-sm-4">
                                        <div class="fw-semibold text-dark">Precio base</div>
                                        <div>Bs <?= number_format((float) ($producto['precio_desde'] ?? $producto['precio']), 2); ?></div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="fw-semibold text-dark">Precio por metro</div>
                                        <div>Bs <?= number_format((float) ($producto['precio_metro'] ?? $producto['precio']), 2); ?></div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="fw-semibold text-dark">Inventario estimado</div>
                                        <div><?= number_format((float) ($producto['stock'] ?? 0)); ?> metros equivalentes</div>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    <span class="badge-soft"><?= (int) ($producto['total_colores'] ?? 0); ?> colores configurados</span>
                                    <span class="badge-soft"><?= (int) ($producto['total_presentaciones'] ?? 0); ?> presentaciones</span>
                                </div>
                                <div class="d-flex flex-wrap gap-2 justify-content-end mt-3">
                                    <form action="<?= BASE_URL ?>/controllers/productos.php" method="POST" class="d-inline">
                                        <input type="hidden" name="accion" value="cambiar_visibilidad">
                                        <input type="hidden" name="id" value="<?= (int) $producto['id']; ?>">
                                        <input type="hidden" name="visible" value="<?= $visible ? 0 : 1; ?>">
                                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                                            <?= $visible ? 'Ocultar en catálogo' : 'Mostrar en catálogo'; ?>
                                        </button>
                                    </form>
                                    <a href="editar_producto.php?id=<?= (int) $producto['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="<?= BASE_URL ?>/controllers/productos.php?accion=eliminar&amp;id=<?= (int) $producto['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">No hay productos registrados todavía.</div>
        <?php endif; ?>
    </div>
</main>

<?php include('includes/footer.php'); ?>
