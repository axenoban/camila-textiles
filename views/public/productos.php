<!-- views/public/productos.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-content">
    <section class="section">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4 mb-4">
                <div>
                    <h1 class="section-title text-start mb-2">Catálogo de telas</h1>
                    <p class="text-muted mb-0">Filtramos y clasificamos los textiles para facilitar tu selección y asegurar disponibilidad inmediata.</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-outline-primary rounded-pill px-4" type="button">Populares</button>
                    <button class="btn btn-outline-primary rounded-pill px-4" type="button">Nuevas</button>
                    <button class="btn btn-outline-primary rounded-pill px-4" type="button">Mayoristas</button>
                </div>
            </div>
            <div class="product-grid">
                <?php foreach ($productos as $producto): ?>
                    <article class="product-card">
                        <?php
                            $unidadVentaClave = $producto['unidad_venta'] === 'rollo' ? 'rollo' : 'metro';
                            $unidadVenta = $unidadVentaClave === 'rollo' ? 'Rollo completo' : 'Metro lineal';
                            $nombreProducto = htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8');
                            $descripcionProducto = htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8');
                            $colorProducto = htmlspecialchars($producto['color'], ENT_QUOTES, 'UTF-8');
                            $imagenProducto = htmlspecialchars($producto['imagen'], ENT_QUOTES, 'UTF-8');
                        ?>
                        <img src="<?= $imagenProducto ?>" alt="<?= $nombreProducto ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0"><?= $nombreProducto ?></h5>
                                <div class="text-end">
                                    <span class="product-price d-block">Bs <?= number_format($producto['precio'], 2, ',', '.') ?></span>
                                    <small class="text-muted">por <?= strtolower($unidadVenta) ?></small>
                                </div>
                            </div>
                            <p class="card-text mb-3"><?= $descripcionProducto ?></p>
                            <ul class="product-details list-unstyled small mb-4">
                                <li><strong>Color:</strong> <?= $colorProducto ?></li>
                                <li><strong>Unidad de venta:</strong> <?= $unidadVenta ?></li>
                            </ul>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge-soft">Stock garantizado</span>
                                <a href="/camila-textil/views/cliente/productos.php" class="btn btn-primary">Reservar</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<?php include('includes/footer.php'); ?>
