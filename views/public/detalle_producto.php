<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../models/producto.php';

$idProducto = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$idProducto) {
    header('Location: ' . BASE_URL . '/views/public/productos.php');
    exit;
}

$productoModel = new Producto();
$producto = $productoModel->obtenerProductoPorId($idProducto);

if (!$producto || !(bool)($producto['visible'] ?? true)) {
    header('Location: ' . BASE_URL . '/views/public/productos.php');
    exit;
}

$colores = $productoModel->obtenerColoresPorProducto($idProducto);
$disponible = ($producto['stock_total'] ?? 0) > 0;
?>
<!-- views/public/detalle_producto.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-content py-6 bg-light">
    <div class="container">

        <!-- 🧭 Encabezado -->
        <div class="text-center mb-5">
            <a href="<?= BASE_URL ?>/views/public/productos.php" class="btn btn-link mb-3">
                <i class="bi bi-arrow-left"></i> Volver al catálogo
            </a>
            <h1 class="fw-bold text-dark mb-2"><?= htmlspecialchars($producto['nombre']); ?></h1>
            <p class="text-muted mb-0">Descubre los colores disponibles, ficha técnica y simula tu pedido.</p>
        </div>

        <div class="row g-5">
            <!-- 🖼️ Imagen + descripción -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <?php if (filter_var($producto['imagen_principal'], FILTER_VALIDATE_URL)): ?>
                        <!-- Si es una URL, mostrarla directamente -->
                        <img src="<?= htmlspecialchars($producto['imagen_principal'], ENT_QUOTES, 'UTF-8'); ?>"
                            alt="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                            class="img-fluid rounded-4 mb-4"
                            style="object-fit: cover; width: 100%; max-height: 400px;">
                    <?php else: ?>
                        <!-- Si es una ruta local, mostrarla desde el directorio de uploads -->
                        <img src="<?= BASE_URL . '/uploads/' . basename($producto['imagen_principal']); ?>"
                            alt="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                            class="img-fluid rounded-4 mb-4"
                            style="object-fit: cover; width: 100%; max-height: 400px;">
                    <?php endif; ?>

                    <h5 class="fw-semibold text-primary mb-3">Descripción</h5>
                    <p class="text-muted"><?= htmlspecialchars($producto['descripcion']); ?></p>

                    <div class="mt-4">
                        <h5 class="fw-semibold text-primary">Ficha técnica</h5>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li><strong>Tipo:</strong> <?= htmlspecialchars($producto['tipo_tela'] ?? '—'); ?></li>
                            <li><strong>Composición:</strong> <?= htmlspecialchars($producto['composicion'] ?? '—'); ?></li>
                            <li><strong>Ancho:</strong> <?= htmlspecialchars($producto['ancho_metros'] ?? '1.60'); ?> m</li>
                            <li><strong>Gramaje:</strong> <?= htmlspecialchars($producto['gramaje'] ?? '—'); ?> g/m²</li>
                            <li><strong>Elasticidad:</strong> <?= htmlspecialchars($producto['elasticidad'] ?? '—'); ?></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 🎨 Colores + simulador -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-semibold mb-0 text-dark">Colores disponibles</h5>
                        <span class="badge bg-<?= $disponible ? 'success' : 'secondary'; ?>">
                            <?= $disponible ? 'Disponible' : 'Agotado'; ?>
                        </span>
                    </div>

                    <?php if (!empty($colores)): ?>
                        <div class="d-flex flex-wrap gap-3 mb-4" id="color-container">
                            <?php foreach ($colores as $color): ?>
                                <button type="button" class="color-chip"
                                    data-precio-metro="<?= (float)$producto['precio_metro']; ?>"
                                    data-precio-rollo="<?= (float)$producto['precio_rollo']; ?>"
                                    data-stock-metro="<?= (float)$color['stock_metros']; ?>"
                                    data-stock-rollo="<?= (float)$color['stock_rollos']; ?>"
                                    data-color-nombre="<?= htmlspecialchars($color['nombre_color']); ?>">
                                    <span class="color-chip-dot" style="background: <?= htmlspecialchars($color['codigo_hex'] ?? '#ccc'); ?>;"></span>
                                    <?= htmlspecialchars($color['nombre_color']); ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Este producto aún no tiene colores configurados.</p>
                    <?php endif; ?>

                    <!-- 🧮 Simulador -->
                    <h5 class="fw-semibold text-dark mb-3">Simula tu pedido</h5>
                    <div class="input-group mb-3">
                        <label class="input-group-text">Unidad</label>
                        <select class="form-select" id="unidad">
                            <option value="metro">Metro</option>
                            <option value="rollo">Rollo</option>
                        </select>
                    </div>

                    <div class="input-group mb-4">
                        <label class="input-group-text">Cantidad</label>
                        <input type="number" class="form-control" id="cantidad" min="1" value="1">
                    </div>

                    <!-- 🧾 Resumen -->
                    <div class="summary-card mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Precio unitario promedio</span>
                            <strong id="precio-unitario">Bs 0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Subtotal estimado</span>
                            <strong id="total-resumen">Bs 0.00</strong>
                        </div>
                        <div class="small text-muted mt-2" id="stock-info"></div>
                    </div>

                    <!-- 🔐 CTA -->
                    <a href="<?= BASE_URL ?>/views/public/login.php" class="btn btn-primary w-100 rounded-pill">
                        Inicia sesión para reservar
                    </a>
                </div>
            </div>

        </div>
    </div>
</main>

<style>
    .color-chip {
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 999px;
        padding: 0.5rem 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        background: #fff;
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .color-chip:hover {
        transform: translateY(-2px);
    }

    .color-chip.active {
        border-color: rgba(13, 110, 253, 0.6);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.2);
    }

    .color-chip-dot {
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 50%;
        border: 1px solid rgba(0, 0, 0, 0.15);
    }

    .summary-card {
        border: 1px dashed rgba(13, 110, 253, 0.3);
        border-radius: 1rem;
        padding: 1rem;
        background: rgba(13, 110, 253, 0.05);
    }
</style>
<script>
    const chips = document.querySelectorAll('.color-chip');
    const precioUnitario = document.getElementById('precio-unitario');
    const totalResumen = document.getElementById('total-resumen');
    const cantidadInput = document.getElementById('cantidad');
    const unidadSelect = document.getElementById('unidad');
    const stockInfo = document.getElementById('stock-info');

    // 💡 Datos del producto
    const metrosPorRollo = <?= (float)$producto['metros_por_rollo']; ?>;

    let coloresSeleccionados = [];
    const formato = new Intl.NumberFormat('es-BO', {
        style: 'currency',
        currency: 'BOB'
    });

    // 🎨 Selección de colores
    chips.forEach(chip => {
        chip.addEventListener('click', () => {
            chip.classList.toggle('active');
            const nombre = chip.dataset.colorNombre;
            const yaSeleccionado = coloresSeleccionados.find(c => c.nombre === nombre);

            if (yaSeleccionado) {
                coloresSeleccionados = coloresSeleccionados.filter(c => c.nombre !== nombre);
            } else {
                coloresSeleccionados.push({
                    nombre,
                    precioMetro: parseFloat(chip.dataset.precioMetro),
                    precioRollo: parseFloat(chip.dataset.precioRollo),
                    stockMetro: parseFloat(chip.dataset.stockMetro),
                    stockRollo: parseFloat(chip.dataset.stockRollo)
                });
            }
            actualizarResumen();
        });
    });

    unidadSelect.addEventListener('change', actualizarResumen);
    cantidadInput.addEventListener('input', actualizarResumen);

    // 🧮 Función principal de cálculo
    function actualizarResumen() {
        const unidad = unidadSelect.value;
        const cantidad = parseFloat(cantidadInput.value) || 0;

        if (coloresSeleccionados.length === 0) {
            precioUnitario.textContent = formato.format(0);
            totalResumen.textContent = formato.format(0);
            stockInfo.textContent = 'Selecciona uno o más colores.';
            return;
        }

        // Obtener precios individuales según unidad
        const precios = coloresSeleccionados.map(c => {
            if (unidad === 'rollo') {
                // 💰 Precio real por rollo = precio_rollo * metros_por_rollo
                return c.precioRollo * metrosPorRollo;
            }
            return c.precioMetro;
        });

        const stocks = coloresSeleccionados.map(c => unidad === 'metro' ? c.stockMetro : c.stockRollo);

        // Promedio de precios y stock mínimo
        const precioPromedio = precios.reduce((a, b) => a + b, 0) / precios.length;
        const stockMinimo = Math.min(...stocks);

        // Calcular total
        const total = cantidad * precioPromedio;

        // Mostrar resultados
        if (unidad === 'rollo') {
            precioUnitario.textContent = `${formato.format(precioPromedio)} (≈ ${metrosPorRollo} metros por rollo)`;
        } else {
            precioUnitario.textContent = formato.format(precioPromedio);
        }

        totalResumen.textContent = formato.format(total);

        // Mostrar stock informativo
        stockInfo.textContent = `${stockMinimo} ${unidad === 'metro' ? 'metros' : 'rollos'} disponibles (colores: ${coloresSeleccionados.map(c => c.nombre).join(', ')}).`;
    }
</script>



<?php include('includes/footer.php'); ?>