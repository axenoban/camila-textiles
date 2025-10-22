<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/../../models/producto.php';
require_once __DIR__ . '/../../models/pedido.php';

$idProducto = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$idProducto) {
    header('Location: ' . BASE_URL . '/views/cliente/productos.php');
    exit;
}

$productoModel = new Producto();
$pedidoModel = new Pedido();
$producto = $productoModel->obtenerProductoPorId($idProducto);

if (!$producto || !(bool)($producto['visible'] ?? true)) {
    header('Location: ' . BASE_URL . '/views/cliente/productos.php');
    exit;
}

$colores = $productoModel->obtenerColoresPorProducto($idProducto);
$mensajeReserva = $_SESSION['reserva_mensaje'] ?? null;
unset($_SESSION['reserva_mensaje']);
$disponible = ($producto['stock_total'] ?? 0) > 0;
?>

<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-content">
    <div class="container">
        <div class="text-center mb-5">
            <a href="<?= BASE_URL ?>/views/cliente/productos.php" class="btn btn-outline-primary rounded-pill mb-3">
                <i class="bi bi-arrow-left"></i> Volver al catálogo
            </a>
            <h1 class="section-heading mb-2"><?= htmlspecialchars($producto['nombre']); ?></h1>
            <p class="section-subtitle">Selecciona uno o varios colores y realiza tu pedido fácilmente.</p>
        </div>

        <?php if ($mensajeReserva): ?>
            <div class="alert alert-success text-center shadow-sm"><?= htmlspecialchars($mensajeReserva); ?></div>
        <?php endif; ?>

        <div class="row g-5 align-items-start">
            <!-- 📸 Imagen + descripción -->
            <div class="col-lg-6">
                <div class="portal-card">
                    <?php if (filter_var($producto['imagen_principal'], FILTER_VALIDATE_URL)): ?>
                        <img src="<?= htmlspecialchars($producto['imagen_principal'], ENT_QUOTES, 'UTF-8'); ?>"
                            class="img-fluid rounded-4 mb-4"
                            style="object-fit: cover; width: 100%; max-height: 420px;"
                            alt="<?= htmlspecialchars($producto['nombre']); ?>">
                    <?php else: ?>
                        <img src="<?= BASE_URL . '/uploads/' . basename($producto['imagen_principal']); ?>"
                            class="img-fluid rounded-4 mb-4"
                            style="object-fit: cover; width: 100%; max-height: 420px;"
                            alt="<?= htmlspecialchars($producto['nombre']); ?>">
                    <?php endif; ?>

                    <h5 class="text-primary fw-semibold mb-3">Descripción</h5>
                    <p class="text-muted"><?= htmlspecialchars($producto['descripcion']); ?></p>

                    <h5 class="text-primary fw-semibold mt-4">Ficha técnica</h5>
                    <ul class="list-unstyled small text-muted">
                        <li><strong>Tipo:</strong> <?= htmlspecialchars($producto['tipo_tela'] ?? '—'); ?></li>
                        <li><strong>Composición:</strong> <?= htmlspecialchars($producto['composicion'] ?? '—'); ?></li>
                        <li><strong>Ancho:</strong> <?= htmlspecialchars($producto['ancho_metros'] ?? '1.60'); ?> m</li>
                        <li><strong>Gramaje:</strong> <?= htmlspecialchars($producto['gramaje'] ?? '—'); ?> g/m²</li>
                        <li><strong>Elasticidad:</strong> <?= htmlspecialchars($producto['elasticidad'] ?? '—'); ?></li>
                    </ul>
                </div>
            </div>

            <!-- 🎨 Pedido + colores -->
            <div class="col-lg-6">
                <div class="portal-card">
                    <form action="<?= BASE_URL ?>/controllers/pedidos_cliente.php" method="POST">
                        <input type="hidden" name="id_producto" value="<?= (int)$producto['id']; ?>">

                        <!-- Sección de colores disponibles -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-semibold mb-0 text-dark">Colores disponibles</h5>
                            <span class="badge bg-<?= $disponible ? 'success' : 'secondary'; ?>">
                                <?= $disponible ? 'Disponible' : 'Agotado'; ?>
                            </span>
                        </div>

                        <!-- Mostrar los colores disponibles -->
                        <?php if (!empty($colores)): ?>
                            <div class="color-grid mb-4">
                                <?php foreach ($colores as $color): ?>
                                    <label class="color-card" title="<?= htmlspecialchars($color['nombre_color']); ?>">
                                        <input type="checkbox" name="id_color[]" value="<?= $color['id']; ?>">
                                        <div class="color-sample" style="background-color: <?= htmlspecialchars($color['codigo_hex'] ?? '#ccc'); ?>;"></div>
                                        <span class="color-name"><?= htmlspecialchars($color['nombre_color']); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">Este producto aún no tiene colores configurados.</p>
                        <?php endif; ?>

                        <!-- Selección de unidad y cantidad -->
                        <h5 class="fw-semibold text-dark mb-3">Unidad y cantidad</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Unidad</label>
                                <select class="form-select" id="unidad" name="unidad" required>
                                    <option value="metro">Metro</option>
                                    <option value="rollo">Rollo</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Cantidad total</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" value="1" required>
                            </div>
                        </div>

                        <!-- Resumen del pedido -->
                        <div class="summary-card mb-4">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Precio unitario</span>
                                <strong id="precio-unitario">Bs 0.00</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Subtotal estimado</span>
                                <strong id="total-resumen">Bs 0.00</strong>
                            </div>
                            <div class="small text-muted mt-2" id="stock-info">Selecciona uno o más colores.</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill">Confirmar pedido</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .color-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 0.75rem;
    }

    .color-card {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fff;
        border: 2px solid transparent;
        border-radius: var(--client-radius);
        padding: 8px 12px;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.08);
    }

    .color-card:hover {
        transform: translateY(-3px);
        border-color: rgba(99, 102, 241, 0.45);
    }

    .color-card input {
        display: none;
    }

    .color-card input:checked~.color-sample,
    .color-card input:checked~.color-name,
    .color-card input:checked {
        color: var(--client-primary-dark);
    }

    .color-card input:checked~.color-card {
        border-color: var(--client-primary);
    }

    .color-card input:checked+.color-sample,
    .color-card input:checked~.color-name {
        color: var(--client-primary);
    }

    .color-card input:checked~.color-sample {
        box-shadow: 0 0 0 3px var(--client-primary);
    }

    .color-card input:checked {
        outline: none;
    }

    .color-card input:checked~.color-sample {
        box-shadow: 0 0 0 3px var(--client-primary);
    }

    .color-card input:checked~.color-card {
        border-color: var(--client-primary);
    }

    .color-card input:checked+.color-sample {
        border-color: var(--client-primary);
    }

    .color-card input:checked+.color-sample {
        box-shadow: 0 0 0 3px var(--client-primary);
    }

    .color-card:has(input:checked) {
        border-color: var(--client-primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
    }

    .color-sample {
        width: 28px;
        height: 28px;
        border-radius: 999px;
        border: 1px solid rgba(15, 23, 42, 0.15);
    }

    .color-name {
        font-weight: 500;
        color: #0f172a;
        font-size: 0.9rem;
    }

    .summary-card {
        border: 1px dashed rgba(99, 102, 241, 0.35);
        border-radius: 1rem;
        padding: 1rem;
        background: rgba(99, 102, 241, 0.05);
    }
</style>
<script>
    const precioUnitarioEl = document.getElementById('precio-unitario');
    const totalResumenEl = document.getElementById('total-resumen');
    const cantidadInput = document.getElementById('cantidad');
    const unidadSelect = document.getElementById('unidad');
    const stockInfo = document.getElementById('stock-info');

    // 🔢 Datos base del producto
    const precioMetro = <?= (float)$producto['precio_metro']; ?>;
    const precioRollo = <?= (float)$producto['precio_rollo']; ?>;
    const metrosPorRollo = <?= (float)$producto['metros_por_rollo']; ?>;

    const formato = new Intl.NumberFormat('es-BO', {
        style: 'currency',
        currency: 'BOB'
    });

    function actualizarResumen() {
        const cantidad = parseFloat(cantidadInput.value) || 0;
        const unidad = unidadSelect.value;
        const seleccionados = document.querySelectorAll('.color-card input:checked').length;

        let precioUnitario, total, infoExtra = "";

        if (unidad === 'rollo') {
            // 💰 Precio real por rollo = precio_rollo * metros_por_rollo
            precioUnitario = precioRollo * metrosPorRollo;
            total = precioUnitario * cantidad * seleccionados;
            infoExtra = `(≈ ${metrosPorRollo} metros por rollo)`;
        } else {
            // 💰 Precio normal por metro
            precioUnitario = precioMetro;
            total = precioMetro * cantidad * seleccionados;
        }

        // Actualizar en pantalla
        precioUnitarioEl.textContent = `${formato.format(precioUnitario)} ${infoExtra}`;
        totalResumenEl.textContent = formato.format(total);

        stockInfo.textContent = seleccionados > 0 
            ? `Has seleccionado ${seleccionados} color${seleccionados > 1 ? 'es' : ''}.`
            : 'Selecciona uno o más colores.';
    }

    // 🧭 Eventos
    document.querySelectorAll('.color-card input').forEach(chk => chk.addEventListener('change', actualizarResumen));
    cantidadInput.addEventListener('input', actualizarResumen);
    unidadSelect.addEventListener('change', actualizarResumen);

    actualizarResumen();
</script>

<?php include('includes/footer.php'); ?>
