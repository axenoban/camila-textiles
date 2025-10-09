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
                    <p class="section-subtitle mb-0">Descubre nuestras telas importadas y reserva en metros o rollos según tus necesidades.</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-outline-primary rounded-pill px-4" type="button">Favoritos</button>
                    <button class="btn btn-outline-primary rounded-pill px-4" type="button">Nuevas tendencias</button>
                    <button class="btn btn-outline-primary rounded-pill px-4" type="button">Mayoristas</button>
                </div>
            </div>
        </section>

        <section class="client-section pt-0">
            <div class="product-grid">
                <?php if (!empty($productos)) : ?>
                    <?php foreach ($productos as $producto) : ?>
                        <?php
                        $precioBase = (float) ($producto['precio_metro'] ?? $producto['precio_desde'] ?? $producto['precio']);
                        $disponible = ($producto['stock'] ?? 0) > 0;
                        ?>
                        <article class="product-card h-100">
                            <img src="<?= htmlspecialchars($producto['imagen'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>">
                            <div class="card-body d-flex flex-column gap-3">
                                <div>
                                    <h5 class="card-title mb-2">
                                        <?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                    </h5>
                                    <p class="text-muted mb-0">
                                        <?= htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8'); ?>
                                    </p>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="product-price">Bs <?= number_format($precioBase, 2); ?></span>
                                    <span class="small text-muted">por metro</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge-soft <?= $disponible ? 'text-success' : 'text-danger'; ?>">
                                        <?= $disponible ? 'Disponible' : 'Agotado'; ?>
                                    </span>
                                    <?php if (!empty($producto['total_colores'])) : ?>
                                        <span class="small text-muted"><?= (int) $producto['total_colores']; ?> colores activos</span>
                                    <?php endif; ?>
                                </div>
                                <a href="<?= BASE_URL ?>/views/cliente/detalle_producto.php?id=<?= (int) $producto['id']; ?>" class="btn btn-primary w-100">
                                    Ver detalles y reservar
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-emoji-neutral"></i></div>
                        <h5 class="fw-semibold">Aún no se han cargado productos</h5>
                        <p class="text-muted mb-0">Cuando el administrador publique nuevas telas las verás inmediatamente aquí.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</main>

<?php include('includes/footer.php'); ?>
