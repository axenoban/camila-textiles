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

if (!$producto || !(bool) ($producto['visible'] ?? true)) {
    header('Location: ' . BASE_URL . '/views/public/productos.php');
    exit;
}

$colores = $productoModel->obtenerColoresPorProducto($idProducto);
$presentaciones = $productoModel->obtenerPresentacionesPorProducto($idProducto);
$existencias = $productoModel->obtenerExistenciasPorProducto($idProducto);

$variantes = [];
foreach ($existencias as $fila) {
    $colorId = (int) $fila['id_color'];
    $presentacionId = (int) $fila['id_presentacion'];
    if (!isset($variantes[$colorId])) {
        $variantes[$colorId] = [];
    }
    $variantes[$colorId][$presentacionId] = [
        'stock' => (float) $fila['stock'],
        'tipo' => $fila['tipo'],
        'metros' => (float) ($fila['metros_por_unidad'] ?? 0),
        'colorNombre' => $fila['color_nombre'],
        'codigoHex' => $fila['codigo_hex'],
    ];
}

$colorDisponible = null;
$presentacionDisponible = null;

foreach ($variantes as $colorId => $presentacionesColor) {
    foreach ($presentacionesColor as $presentacionId => $detalle) {
        if ($detalle['stock'] > 0) {
            $colorDisponible = $colorId;
            $presentacionDisponible = $presentacionId;
            break 2;
        }
    }
}

if ($colorDisponible === null && !empty($colores)) {
    $colorDisponible = (int) $colores[0]['id'];
}

if ($presentacionDisponible === null && !empty($presentaciones)) {
    $presentacionDisponible = (int) $presentaciones[0]['id'];
}

$datosVariantes = [
    'colores' => $colores,
    'presentaciones' => $presentaciones,
    'matriz' => $variantes,
];

$disponible = (float) ($producto['stock_total'] ?? $producto['stock'] ?? 0) > 0;
?>
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<style>
    .detail-card {
        background: #fff;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
    }

    .color-chip {
        border: 1px solid rgba(15, 23, 42, 0.1);
        border-radius: 999px;
        padding: 0.5rem 1rem;
        background: #fff;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .color-chip.active {
        border-color: rgba(13, 110, 253, 0.5);
        box-shadow: 0 8px 20px rgba(13, 110, 253, 0.15);
    }

    .color-chip-dot {
        width: 1.75rem;
        height: 1.75rem;
        border-radius: 50%;
        border: 1px solid rgba(15, 23, 42, 0.15);
    }

    .option-card {
        display: block;
        border: 1px solid rgba(15, 23, 42, 0.12);
        border-radius: 0.75rem;
        padding: 1rem 1.25rem;
        background: #fff;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .option-card.active {
        border-color: rgba(25, 135, 84, 0.5);
        box-shadow: 0 8px 20px rgba(25, 135, 84, 0.1);
    }

    .summary-card {
        border: 1px dashed rgba(13, 110, 253, 0.4);
        border-radius: 0.75rem;
        padding: 1.25rem;
        background: rgba(13, 110, 253, 0.04);
    }

    @media (max-width: 991.98px) {
        .detail-card {
            padding: 1.5rem;
        }
    }
</style>

<main class="main-content">
    <section class="section">
        <div class="container">
            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
                <div>
                    <a class="btn btn-link px-0" href="<?= BASE_URL ?>/views/public/productos.php"><i class="bi bi-arrow-left"></i> Volver al catálogo</a>
                    <h1 class="section-title mb-1"><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?></h1>
                    <p class="text-muted mb-0">Analiza colores, presentaciones y precios para planificar tus pedidos con precisión.</p>
                </div>
                <div class="text-lg-end">
                    <span class="badge bg-light text-primary fw-semibold">Precio desde $<?= number_format((float) ($producto['precio_desde'] ?? $producto['precio']), 2); ?></span>
                    <div class="small text-muted">Estado: <?= $disponible ? 'Disponible' : 'Agotado temporalmente'; ?></div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="detail-card h-100">
                        <img src="<?= htmlspecialchars($producto['imagen'], ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid rounded-4 mb-3" alt="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>">
                        <p class="text-muted mb-4"><?= htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <div class="detail-card bg-light-subtle border-0">
                            <h5 class="fw-semibold">Pensado para producciones exigentes</h5>
                            <ul class="list-unstyled mb-0">
                                <li><i class="bi bi-check2-circle text-success"></i> Elasticidad de retorno ideal para blusas, bodys y básicos premium.</li>
                                <li><i class="bi bi-check2-circle text-success"></i> Ancho estándar de 1,60 m que optimiza el corte industrial.</li>
                                <li><i class="bi bi-check2-circle text-success"></i> Tintes reactivos que resisten procesos de lavandería comercial.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="detail-card h-100">
                        <div class="mb-4">
                            <h5 class="fw-semibold mb-3">Colores disponibles</h5>
                            <?php if (!empty($colores)): ?>
                                <div class="d-flex flex-wrap gap-3" id="grupo-colores">
                                    <?php foreach ($colores as $color): ?>
                                        <?php
                                            $activo = (int) $color['id'] === (int) ($colorDisponible ?? $color['id']);
                                            $codigo = $color['codigo_hex'] ?? '#cccccc';
                                        ?>
                                        <button type="button" class="color-chip <?= $activo ? 'active' : ''; ?>" data-color-id="<?= (int) $color['id']; ?>" aria-label="<?= htmlspecialchars($color['nombre'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <span class="color-chip-dot" style="background: <?= htmlspecialchars($codigo, ENT_QUOTES, 'UTF-8'); ?>"></span>
                                            <span><?= htmlspecialchars($color['nombre'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted mb-0">Pronto añadiremos la paleta completa de este textil.</p>
                            <?php endif; ?>
                        </div>
                        <div class="mb-4">
                            <h5 class="fw-semibold mb-3">Presentaciones de venta</h5>
                            <?php if (!empty($presentaciones)): ?>
                                <div class="row g-3" id="grupo-presentaciones">
                                    <?php foreach ($presentaciones as $presentacion): ?>
                                        <?php $activo = (int) $presentacion['id'] === (int) ($presentacionDisponible ?? $presentacion['id']); ?>
                                        <div class="col-sm-6">
                                            <div class="option-card <?= $activo ? 'active' : ''; ?>" data-presentacion-id="<?= (int) $presentacion['id']; ?>" data-tipo="<?= htmlspecialchars($presentacion['tipo'], ENT_QUOTES, 'UTF-8'); ?>" data-precio="<?= number_format((float) $presentacion['precio'], 2, '.', ''); ?>" data-metros="<?= number_format((float) ($presentacion['metros_por_unidad'] ?? 0), 2, '.', ''); ?>">
                                                <strong class="d-block text-capitalize mb-1"><?= htmlspecialchars($presentacion['tipo'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                                <span class="d-block text-muted small">$<?= number_format((float) $presentacion['precio'], 2); ?> <?= $presentacion['tipo'] === 'rollo' ? 'por rollo' : 'por metro'; ?></span>
                                                <?php if ($presentacion['tipo'] === 'rollo'): ?>
                                                    <span class="badge-soft mt-2">≈ <?= number_format((float) ($presentacion['metros_por_unidad'] ?? 0), 0); ?> metros por rollo</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted mb-0">Configura las presentaciones desde el panel administrativo.</p>
                            <?php endif; ?>
                        </div>
                        <div class="mb-4">
                            <h5 class="fw-semibold mb-3">Simula tu pedido</h5>
                            <div class="input-group">
                                <label class="input-group-text" for="cantidad">Unidades</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" value="1">
                            </div>
                            <div class="form-text" id="ayuda-disponibilidad"></div>
                        </div>
                        <div class="summary-card mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted">Precio unitario</span>
                                <strong id="precio-unitario">$0.00</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Subtotal estimado</span>
                                <strong id="total-resumen">$0.00</strong>
                            </div>
                            <div class="small text-muted mt-2" id="equivalencia-texto"></div>
                        </div>
                        <a href="<?= BASE_URL ?>/views/public/login.php" class="btn btn-primary w-100" id="cta-reservar">Inicia sesión para reservar</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    const dataVariantes = <?= json_encode($datosVariantes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
    const colorInput = document.createElement('input');
    const presentacionInput = document.createElement('input');
    colorInput.value = '<?= (int) ($colorDisponible ?? 0); ?>';
    presentacionInput.value = '<?= (int) ($presentacionDisponible ?? 0); ?>';

    const cantidadInput = document.getElementById('cantidad');
    const precioUnitario = document.getElementById('precio-unitario');
    const totalResumen = document.getElementById('total-resumen');
    const textoEquivalencia = document.getElementById('equivalencia-texto');
    const ayudaDisponibilidad = document.getElementById('ayuda-disponibilidad');
    const ctaReservar = document.getElementById('cta-reservar');

    const formatoMoneda = new Intl.NumberFormat('es-PE', { style: 'currency', currency: 'PEN' });

    function obtenerDetalleSeleccion(colorId, presentacionId) {
        if (!dataVariantes.matriz[colorId] || !dataVariantes.matriz[colorId][presentacionId]) {
            return null;
        }
        const presentacion = dataVariantes.presentaciones.find(p => parseInt(p.id, 10) === parseInt(presentacionId, 10));
        return {
            ...dataVariantes.matriz[colorId][presentacionId],
            precio: presentacion ? parseFloat(presentacion.precio) : 0,
            tipo: presentacion ? presentacion.tipo : 'metro',
            metrosPorUnidad: presentacion ? parseFloat(presentacion.metros_por_unidad || 1) : 1
        };
    }

    function actualizarEstado() {
        const colorId = parseInt(colorInput.value, 10);
        const presentacionId = parseInt(presentacionInput.value, 10);
        const detalle = obtenerDetalleSeleccion(colorId, presentacionId);

        if (!detalle) {
            precioUnitario.textContent = '$0.00';
            totalResumen.textContent = '$0.00';
            textoEquivalencia.textContent = '';
            ayudaDisponibilidad.textContent = 'Selecciona una combinación disponible para conocer precios.';
            ctaReservar.classList.add('disabled');
            ctaReservar.setAttribute('aria-disabled', 'true');
            return;
        }

        const stock = detalle.stock;
        const tipo = detalle.tipo;
        const metros = tipo === 'rollo' ? Math.round(detalle.metrosPorUnidad) : 1;
        const precio = detalle.precio;

        precioUnitario.textContent = formatoMoneda.format(precio);
        const cantidad = parseInt(cantidadInput.value, 10) || 0;
        totalResumen.textContent = formatoMoneda.format(precio * cantidad);

        if (stock <= 0) {
            ayudaDisponibilidad.textContent = 'Agotado en esta combinación. Escríbenos para programar reposición.';
            textoEquivalencia.textContent = '';
            ctaReservar.classList.add('disabled');
            ctaReservar.setAttribute('aria-disabled', 'true');
        } else {
            const equivalenciaTexto = tipo === 'rollo'
                ? `Cada rollo equivale aproximadamente a ${metros} metros.`
                : 'Compra por metro con corte industrial incluido.';
            textoEquivalencia.textContent = equivalenciaTexto;
            ayudaDisponibilidad.textContent = 'Disponible para reservas inmediatas.';
            ctaReservar.classList.remove('disabled');
            ctaReservar.removeAttribute('aria-disabled');
        }
    }

    document.querySelectorAll('.color-chip').forEach(chip => {
        chip.addEventListener('click', () => {
            document.querySelectorAll('.color-chip').forEach(c => c.classList.remove('active'));
            chip.classList.add('active');
            colorInput.value = chip.dataset.colorId;
            actualizarEstado();
        });
    });

    document.querySelectorAll('.option-card').forEach(option => {
        option.addEventListener('click', () => {
            document.querySelectorAll('.option-card').forEach(op => op.classList.remove('active'));
            option.classList.add('active');
            presentacionInput.value = option.dataset.presentacionId;
            actualizarEstado();
        });
    });

    cantidadInput.addEventListener('change', actualizarEstado);
    cantidadInput.addEventListener('keyup', actualizarEstado);

    actualizarEstado();
</script>

<?php include('includes/footer.php'); ?>
