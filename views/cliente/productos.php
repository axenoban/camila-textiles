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
                        <img src="<?= $producto['imagen'] ?>" class="img-fluid rounded-4 mb-3" alt="<?= $producto['nombre'] ?>">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="fw-semibold mb-1"><?= $producto['nombre'] ?></h5>
                            <span class="badge bg-light text-primary fw-semibold">$<?= number_format($producto['precio'], 2) ?></span>
                        </div>
                        <p class="text-muted mb-4"><?= $producto['descripcion'] ?></p>
                        <a href="agregar_pedido.php?id=<?= $producto['id'] ?>" class="btn btn-primary w-100">Reservar ahora</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
