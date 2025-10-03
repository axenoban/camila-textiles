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
                        <img src="<?= $producto['imagen'] ?>" alt="<?= $producto['nombre'] ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0"><?= $producto['nombre'] ?></h5>
                                <span class="product-price">$<?= number_format($producto['precio'], 2) ?></span>
                            </div>
                            <p class="card-text mb-4"><?= $producto['descripcion'] ?></p>
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
