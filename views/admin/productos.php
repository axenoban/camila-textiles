<?php
require_once __DIR__ . '/../../models/producto.php';

$productoModel = new Producto();
$productos = $productoModel->obtenerTodosLosProductos();
$variantesCatalogo = $productoModel->obtenerVariantesCatalogo();

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
<!-- views/admin/productos.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <?php if ($status && isset($mensajes[$status])) { ?>
            <div class="alert alert-<?= htmlspecialchars($mensajes[$status]['type'], ENT_QUOTES, 'UTF-8'); ?> alert-dismissible fade show" role="alert" data-auto-dismiss="true">
                <?= htmlspecialchars($mensajes[$status]['text'], ENT_QUOTES, 'UTF-8'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php } ?>

        <header class="page-header text-center text-lg-start">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h1 class="page-title mb-2">Gestión de productos</h1>
                    <p class="page-subtitle mb-0">Controla el catálogo de telas, su disponibilidad y precios para asegurar una experiencia de compra impecable.</p>
                </div>
                <a href="agregar_producto.php" class="btn btn-success">Agregar producto</a>
            </div>
        </header>

        <?php if (!empty($productos)) { ?>
            <div class="row g-4">
                <?php foreach ($productos as $producto) {
                    $productoId = (int) ($producto['id'] ?? 0);
                    $visible = (bool) ($producto['visible'] ?? false);
                    $detalleVariantes = $variantesCatalogo[$productoId] ?? ['colores' => [], 'presentaciones' => [], 'variantes' => []];
                    $coloresConfigurados = count($detalleVariantes['colores']);
                    $presentacionesConfiguradas = count($detalleVariantes['presentaciones']);
                    $variantes = $detalleVariantes['variantes'];
                    $collapseId = 'variantes-' . $productoId;
                    $accordionId = 'accordion-' . $productoId;
                    ?>
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
                                        <span class="badge-soft"><?= $coloresConfigurados ?: (int) ($producto['total_colores'] ?? 0); ?> colores configurados</span>
                                        <span class="badge-soft"><?= $presentacionesConfiguradas ?: (int) ($producto['total_presentaciones'] ?? 0); ?> presentaciones</span>
                                    </div>

                                    <div class="accordion mt-3" id="<?= htmlspecialchars($accordionId, ENT_QUOTES, 'UTF-8'); ?>">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-<?= htmlspecialchars((string) $productoId, ENT_QUOTES, 'UTF-8'); ?>">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?= htmlspecialchars($collapseId, ENT_QUOTES, 'UTF-8'); ?>" aria-expanded="false" aria-controls="<?= htmlspecialchars($collapseId, ENT_QUOTES, 'UTF-8'); ?>">
                                                    Configuración comercial (colores y presentaciones)
                                                </button>
                                            </h2>
                                            <div id="<?= htmlspecialchars($collapseId, ENT_QUOTES, 'UTF-8'); ?>" class="accordion-collapse collapse" data-bs-parent="#<?= htmlspecialchars($accordionId, ENT_QUOTES, 'UTF-8'); ?>">
                                                <div class="accordion-body">
                                                    <?php if (!empty($variantes)) { ?>
                                                        <div class="table-responsive">
                                                            <table class="table table-modern table-sm align-middle mb-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Color</th>
                                                                        <th>Presentación</th>
                                                                        <th>Precio</th>
                                                                        <th>Equivalencia</th>
                                                                        <th class="text-end">Stock</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($variantes as $variante) {
                                                                        $metros = (float) ($variante['metros_por_unidad'] ?? 0);
                                                                        $equivalencia = $variante['presentacion_tipo'] === 'rollo'
                                                                            ? ($metros > 0 ? number_format($metros, 0) . ' m por rollo' : 'n/d')
                                                                            : 'Venta por metro';
                                                                        ?>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="d-flex align-items-center gap-2">
                                                                                    <span class="badge rounded-pill" style="background: <?= htmlspecialchars($variante['codigo_hex'] ?? '#d1d5db', ENT_QUOTES, 'UTF-8'); ?>; width: 1.5rem; height: 1.5rem;"></span>
                                                                                    <span><?= htmlspecialchars($variante['color_nombre'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                                                </div>
                                                                            </td>
                                                                            <td class="text-capitalize"><?= htmlspecialchars($variante['presentacion_tipo'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                                            <td>Bs <?= number_format((float) $variante['precio'], 2); ?></td>
                                                                            <td><?= htmlspecialchars($equivalencia, ENT_QUOTES, 'UTF-8'); ?></td>
                                                                            <td class="text-end"><?= number_format((float) $variante['stock'], 2); ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    <?php } else { ?>
                                                        <p class="text-muted mb-0">Aún no se configuraron combinaciones de color y presentación para este producto.</p>
                                                    <?php } ?>
                                                    <div class="mt-3 small text-muted">Gestiona colores, presentaciones y existencias desde la sección de inventario o solicitando soporte técnico.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 justify-content-end mt-3">
                                        <form action="<?= BASE_URL ?>/controllers/productos.php" method="POST" class="d-inline">
                                            <input type="hidden" name="accion" value="cambiar_visibilidad">
                                            <input type="hidden" name="id" value="<?= $productoId; ?>">
                                            <input type="hidden" name="visible" value="<?= $visible ? 0 : 1; ?>">
                                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                                <?= $visible ? 'Ocultar en catálogo' : 'Mostrar en catálogo'; ?>
                                            </button>
                                        </form>
                                        <a href="editar_producto.php?id=<?= $productoId; ?>" class="btn btn-warning btn-sm">Editar</a>
                                        <a href="<?= BASE_URL ?>/controllers/productos.php?accion=eliminar&amp;id=<?= $productoId; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="alert alert-info" role="alert">No hay productos registrados todavía.</div>
        <?php } ?>
    </div>
</main>

<?php include('includes/footer.php'); ?>
