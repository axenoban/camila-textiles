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
                    <h1 class="section-title text-start mb-2">Catálogo exclusivo Camila Textil</h1>
                    <p class="text-muted mb-0">Planifica tus producciones combinando colores, formatos y disponibilidad sin salir del panel.</p>
                </div>
                <div class="d-flex gap-2 flex-wrap justify-content-md-end">
                    <button class="btn btn-outline-primary rounded-pill px-4" type="button"><i class="bi bi-graph-up"></i> En tendencia</button>
                    <button class="btn btn-outline-primary rounded-pill px-4" type="button"><i class="bi bi-box-seam"></i> Mayoristas</button>
                    <button class="btn btn-outline-primary rounded-pill px-4" type="button"><i class="bi bi-lightning"></i> Reposición rápida</button>
                </div>
            </div>

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
                        <article class="product-card">
                            <div class="product-media">
                                <img src="<?= htmlspecialchars($producto['imagen'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>">
                                <span class="availability <?= $disponible ? 'available' : 'soldout'; ?>">
                                    <?= $disponible ? 'Disponible' : 'Agotado'; ?>
                                </span>
                            </div>
                            <div class="card-body d-flex flex-column gap-3">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <h5 class="card-title mb-0 flex-grow-1">
                                        <?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                    </h5>
                                </div>
                                <p class="text-muted mb-0">
                                    <?= htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8'); ?>
                                </p>
                                <?php if (!empty($paleta)) { ?>
                                    <div>
                                        <div class="small text-muted mb-2">Paleta destacada</div>
                                        <div class="color-palette" aria-label="Colores disponibles">
                                            <?php foreach ($paleta as $muestra) { ?>
                                                <span class="color-chip" title="<?= htmlspecialchars($muestra['nombre'], ENT_QUOTES, 'UTF-8'); ?>" style="background-color: <?= htmlspecialchars($muestra['hex'], ENT_QUOTES, 'UTF-8'); ?>"></span>
                                            <?php } ?>
                                            <?php if (($producto['total_colores'] ?? 0) > count($paleta)) { ?>
                                                <span class="color-chip more">+<?= (int) $producto['total_colores'] - count($paleta); ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="d-flex justify-content-between align-items-end">
                                    <div>
                                        <span class="product-price d-block">Bs <?= number_format($precioBase, 2); ?></span>
                                        <small class="text-muted">Tarifa base por metro</small>
                                    </div>
                                    <div class="text-end small text-muted">
                                        <div><i class="bi bi-palette"></i> <?= (int) ($producto['total_colores'] ?? 0); ?> colores</div>
                                        <div><i class="bi bi-columns-gap"></i> <?= (int) ($producto['total_presentaciones'] ?? 0); ?> presentaciones</div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column flex-sm-row gap-2">
                                    <a href="<?= BASE_URL ?>/views/cliente/detalle_producto.php?id=<?= (int) $producto['id']; ?>" class="btn btn-primary flex-grow-1">
                                        Ver detalles y reservar
                                    </a>
                                    <a href="<?= BASE_URL ?>/views/public/detalle_producto.php?id=<?= (int) $producto['id']; ?>" class="btn btn-outline-primary flex-grow-1">
                                        Vista pública
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php } ?>
                <?php } else { ?>
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-emoji-neutral"></i></div>
                        <h5 class="fw-semibold">Aún no se han cargado productos</h5>
                        <p class="text-muted mb-0">Cuando se publiquen nuevas telas estarán disponibles para reservar al instante.</p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</main>

<?php include('includes/footer.php'); ?>
