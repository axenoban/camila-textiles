<!-- views/admin/productos.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
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
                            <th>Color</th>
                            <th>Unidad de venta</th>
                            <th>Precio (Bs)</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                            <?php
                                $nombreProducto = htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8');
                                $descripcionProducto = htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8');
                                $colorProducto = htmlspecialchars($producto['color'], ENT_QUOTES, 'UTF-8');
                                $unidadVenta = $producto['unidad_venta'] === 'rollo' ? 'Rollo completo' : 'Metro lineal';
                            ?>
                            <tr>
                                <td><?= $producto['id'] ?></td>
                                <td><?= $nombreProducto ?></td>
                                <td><?= $descripcionProducto ?></td>
                                <td><?= $colorProducto ?></td>
                                <td><?= $unidadVenta ?></td>
                                <td><?= 'Bs ' . number_format($producto['precio'], 2, ',', '.') ?></td>
                                <td class="text-nowrap">
                                    <a href="editar_producto.php?id=<?= $producto['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="eliminar_producto.php?id=<?= $producto['id'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
