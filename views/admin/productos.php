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
            'no_encontrado' => ['type' => 'warning', 'text' => 'El producto solicitado no existe.'],
            'error' => ['type' => 'danger', 'text' => 'No se pudo completar la operación solicitada.'],
        ];

        if ($status && isset($mensajes[$status])): ?>
        <div class="alert alert-<?= $mensajes[$status]['type']; ?> alert-dismissible fade show" role="alert">
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
        <div class="table-shell">
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($productos)): ?>
                            <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><?= (int) $producto['id']; ?></td>
                                <td><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= '$' . number_format((float) $producto['precio'], 2); ?></td>
                                <td class="text-nowrap">
                                    <a href="editar_producto.php?id=<?= (int) $producto['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="eliminar_producto.php?id=<?= (int) $producto['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No hay productos registrados todavía.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
       </div>   
    </div>
</main>

<?php include('includes/footer.php'); ?>
