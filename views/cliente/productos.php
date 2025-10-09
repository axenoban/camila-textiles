<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/../../models/producto.php';

$productoModel = new Producto();
$productos = $productoModel->obtenerProductosVisibles();
?>
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-content">
    <section class="section">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4 mb-4">
                <div>
                    <h1 class="section-title text-start mb-2">Catálogo exclusivo para clientes</h1>
                    <p class="text-muted mb-0">
                        Filtramos y clasificamos los textiles para facilitar tu selección 
                        y asegurar disponibilidad inmediata.
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-outline-primary rounded-pill px-4" type="button">Favoritos</button>
                    <button class="btn btn-outline-primary rounded-pill px-4" type="button">Nuevas</button>
                    <button class="btn btn-outline-primary rounded-pill px-4" type="button">Mayoristas</button>
                </div>
            </div>

            <div class="product-grid">
                <?php if (!empty($productos)): ?>
                    <?php foreach ($productos as $producto): ?>
                        <article class="product-card">
                            <img src="<?= htmlspecialchars($producto['imagen'], ENT_QUOTES, 'UTF-8'); ?>" 
                                 alt="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>">

                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title mb-0"><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?></h5>
                                    <span class="product-price">
                                        $<?= number_format($producto['precio_desde'] ?? $producto['precio'], 2) ?>
                                    </span>
                                </div>

                                <p class="card-text mb-4"><?= htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8'); ?></p>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge-soft <?= ($producto['stock'] ?? 0) > 0 ? 'text-success' : 'text-danger'; ?>">
                                        <?= ($producto['stock'] ?? 0) > 0 
                                            ? 'Stock disponible: ' . (int) $producto['stock'] 
                                            : 'Sin stock en este momento'; ?>
                                    </span>

                                    <!-- 🔗 Detalle del cliente -->
                                    <a href="<?= BASE_URL ?>/views/cliente/detalle_producto_cliente.php?id=<?= (int) $producto['id']; ?>" 
                                       class="btn btn-primary">Ver detalles</a>
                                </div>

                                <span class="badge-soft <?= ($producto['stock'] ?? 0) > 0 ? 'text-success' : 'text-danger'; ?>">
                                    <?= ($producto['stock'] ?? 0) > 0 ? 'Disponible' : 'Agotado'; ?>
                                </span>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-emoji-neutral"></i></div>
                        <h5 class="fw-semibold">Aún no se han cargado productos</h5>
                        <p class="text-muted mb-0">
                            Cuando el administrador publique nuevas telas, 
                            aparecerán automáticamente aquí.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php include('includes/footer.php'); ?>
