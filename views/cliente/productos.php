<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/../../models/producto.php';

$productoModel = new Producto();
$productos = $productoModel->obtenerProductosVisibles();
?>
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
                    <div class="portal-card h-100 d-flex flex-column">
                        <img src="<?= htmlspecialchars($producto['imagen'], ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid rounded-4 mb-3" alt="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="fw-semibold mb-1"><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?></h5>
                            <span class="badge bg-light text-primary fw-semibold">$<?= number_format((float) ($producto['precio_desde'] ?? $producto['precio']), 2); ?> <small class="text-muted">desde</small></span>
                        </div>
                        <p class="text-muted mb-4 flex-grow-1"><?= htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge-soft <?= ($producto['stock'] ?? 0) > 0 ? 'text-success' : 'text-danger'; ?>">
                                    <?= ($producto['stock'] ?? 0) > 0 ? 'Disponibilidad confirmada' : 'Agotado temporalmente'; ?>
                                </span>
                                <?php if ((int) ($producto['total_colores'] ?? 0) > 0): ?>
                                    <span class="small text-muted"><?= (int) $producto['total_colores']; ?> colores activos</span>
                                <?php endif; ?>
                            </div>
                            <a href="<?= BASE_URL ?>/views/cliente/detalle_producto.php?id=<?= (int) $producto['id']; ?>" class="btn btn-primary">Ver detalle y reservar</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info mb-0" role="alert">
                        Aún no hay productos disponibles para reservar. Vuelve más tarde para descubrir nuevas telas.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
