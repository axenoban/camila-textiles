<?php
require_once __DIR__ . '/../../models/producto.php';

$productoModel = new Producto();
$productos = $productoModel->obtenerProductosVisibles();
?>
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
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $producto): ?>
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="portal-card h-100">
                            <img src="<?= htmlspecialchars($producto['imagen'], ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid rounded-4 mb-3" alt="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="fw-semibold mb-1"><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?></h5>
                                <span class="client-pill">$<?= number_format($producto['precio'], 2) ?></span>
                            </div>
                            <p class="text-muted mb-4"><?= htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <a href="agregar_pedido.php?id=<?= $producto['id'] ?>" class="btn btn-primary w-100">Reservar ahora</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="portal-card text-center">
                        <i class="bi bi-emoji-smile display-5 text-primary mb-3"></i>
                        <p class="text-muted mb-0">Aún no hay productos disponibles en este momento. Vuelve más tarde o contacta al equipo de ventas.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
