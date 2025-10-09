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
                <?php if (!empty($productos)) { ?>
                    <?php foreach ($productos as $producto) {
                        $precioBase = (float) ($producto['precio_metro'] ?? $producto['precio_desde'] ?? $producto['precio']);
                        $disponible = ($producto['stock'] ?? 0) > 0;
                        $paleta = [];

                        if (!empty($producto['colores_preview'])) {
                            $candidatos = array_filter(explode('||', (string) $producto['colores_preview']));
                            foreach ($candidatos as $entrada) {
                                $partes = explode('::', $entrada);
                                $nombreColor = trim($partes[0] ?? 'Color personalizado');
                                $hexColor = trim($partes[1] ?? '');
                                if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $hexColor)) {
                                    $hexColor = '';
                                }
                                $paleta[] = [
                                    'nombre' => $nombreColor,
                                    'hex' => $hexColor ?: '#d1d5db',
                                ];
                                if (count($paleta) >= 4) {
                                    break;
                                }
                            }
                        }
                    ?>
                    <article class="product-card h-100">
                        <div class="product-media">
                            <img src="<?= htmlspecialchars($producto['imagen'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>">
                            <span class="availability <?= $disponible ? 'available' : 'soldout'; ?>">
                                <?= $disponible ? 'Disponible' : 'Agotado'; ?>
                            </span>
                        </div>
                        <div class="card-body d-flex flex-column gap-3">
                            <div>
                                <h5 class="card-title mb-2">
                                    <?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                </h5>
                                <p class="text-muted mb-0">
                                    <?= htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8'); ?>
                                </p>
                            </div>
                            <?php if (!empty($paleta)) { ?>
                                <div class="color-palette" aria-label="Colores disponibles">
                                    <?php foreach ($paleta as $muestra) { ?>
                                        <span class="color-chip" title="<?= htmlspecialchars($muestra['nombre'], ENT_QUOTES, 'UTF-8'); ?>" style="background-color: <?= htmlspecialchars($muestra['hex'], ENT_QUOTES, 'UTF-8'); ?>"></span>
                                    <?php } ?>
                                    <?php if (($producto['total_colores'] ?? 0) > count($paleta)) { ?>
                                        <span class="color-chip more">+<?= (int) $producto['total_colores'] - count($paleta); ?></span>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="product-price">Bs <?= number_format($precioBase, 2); ?></span>
                                <span class="small text-muted">por metro</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-muted">Presentaciones: <?= (int) ($producto['total_presentaciones'] ?? 0); ?></span>
                                <span class="small text-muted">Colores: <?= (int) ($producto['total_colores'] ?? 0); ?></span>
                            </div>
                            <a href="<?= BASE_URL ?>/views/cliente/detalle_producto.php?id=<?= (int) $producto['id']; ?>" class="btn btn-primary w-100">
                                Ver detalles y reservar
                            </a>
                        </div>
                    </article>
                    <?php } ?>
                <?php } else { ?>
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-emoji-neutral"></i></div>
                        <h5 class="fw-semibold">Aún no se han cargado productos</h5>
                        <p class="text-muted mb-0">Cuando el administrador publique nuevas telas las verás inmediatamente aquí.</p>
                    </div>
                <?php } ?>
            </div>
        </section>
    </div>
</main>

<?php include('includes/footer.php'); ?>
