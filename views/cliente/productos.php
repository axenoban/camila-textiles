<!-- views/cliente/productos.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-area">
    <div class="container-fluid px-4 px-lg-5">
        <section class="client-section">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h1 class="section-heading mb-2">Catálogo exclusivo para clientes</h1>
                    <p class="section-subtitle mb-0">Selecciona las telas ideales y realiza tu pedido con disponibilidad confirmada.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-outline-primary" type="button">Favoritos</button>
                    <button class="btn btn-outline-primary" type="button">Nuevas tendencias</button>
                </div>
            </div>
        </section>
        <div class="row g-4">
            <?php foreach ($productos as $producto): ?>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="portal-card h-100">
                        <?php
                            $unidadVentaClave = $producto['unidad_venta'] === 'rollo' ? 'rollo' : 'metro';
                            $unidadVenta = $unidadVentaClave === 'rollo' ? 'Rollo completo' : 'Metro lineal';
                            $nombreProducto = htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8');
                            $descripcionProducto = htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8');
                            $colorProducto = htmlspecialchars($producto['color'], ENT_QUOTES, 'UTF-8');
                            $imagenProducto = htmlspecialchars($producto['imagen'], ENT_QUOTES, 'UTF-8');
                        ?>
                        <img src="<?= $imagenProducto ?>" class="img-fluid rounded-4 mb-3" alt="<?= $nombreProducto ?>">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="fw-semibold mb-1"><?= $nombreProducto ?></h5>
                            <div class="text-end">
                                <span class="badge bg-light text-primary fw-semibold d-block">Bs <?= number_format($producto['precio'], 2, ',', '.') ?></span>
                                <small class="text-muted">por <?= strtolower($unidadVenta) ?></small>
                            </div>
                        </div>
                        <p class="text-muted mb-3"><?= $descripcionProducto ?></p>
                        <ul class="list-unstyled small text-muted mb-4">
                            <li><strong>Color:</strong> <?= $colorProducto ?></li>
                            <li><strong>Unidad de venta:</strong> <?= $unidadVenta ?></li>
                        </ul>
                        <a href="agregar_pedido.php?id=<?= $producto['id'] ?>" class="btn btn-primary w-100">Reservar ahora</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
