<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/../../models/producto.php';

$idProducto = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$idProducto) {
    header('Location: ' . BASE_URL . '/views/cliente/productos.php');
    exit;
}

$productoModel = new Producto();
$producto = $productoModel->obtenerProductoPorId($idProducto);

if (!$producto || !(bool) ($producto['visible'] ?? true)) {
    header('Location: ' . BASE_URL . '/views/cliente/productos.php');
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

$mensajeReserva = $_SESSION['reserva_mensaje'] ?? null;
$tipoReserva = $_SESSION['reserva_tipo'] ?? null;
unset($_SESSION['reserva_mensaje'], $_SESSION['reserva_tipo']);

$datosVariantes = [
    'colores' => $colores,
    'presentaciones' => $presentaciones,
    'matriz' => $variantes,
];
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

    .feature-box {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 0.75rem;
        padding: 1.25rem;
        background: rgba(13, 110, 253, 0.03);
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

    .color-chip-label {
        font-size: 0.95rem;
        font-weight: 500;
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

<main class="main-area">
    <div class="container-fluid px-4 px-lg-5">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
            <div>
                <a class="btn btn-link px-0" href="<?= BASE_URL ?>/views/cliente/productos.php"><i class="bi bi-arrow-left"></i> Volver al catálogo</a>
                <h1 class="section-heading mb-1"><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?></h1>
                <p class="section-subtitle mb-0">Gestiona tu reserva escogiendo color, formato y volumen en un solo lugar.</p>
            </div>
            <div class="text-lg-end">
                <span class="badge bg-light text-primary fw-semibold">Precio desde Bs <?= number_format((float) ($producto['precio_desde'] ?? $producto['precio']), 2); ?></span>
                <div class="small text-muted">Stock equivalente: <?= (int) ($producto['stock_total'] ?? $producto['stock'] ?? 0); ?> metros</div>
            </div>
        </div>

        <?php if ($mensajeReserva): ?>
            <div class="alert alert-<?= $tipoReserva === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert" data-auto-dismiss="true">
                <?= htmlspecialchars($mensajeReserva, ENT_QUOTES, 'UTF-8'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="detail-card h-100">
                    <img src="<?= htmlspecialchars($producto['imagen'], ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid rounded-4 mb-3" alt="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>">
                    <p class="text-muted mb-4"><?= htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <div class="feature-box">
                        <h5 class="fw-semibold">Por qué las marcas confían en esta tela</h5>
                        <ul class="list-unstyled mb-0">
                            <li><i class="bi bi-check2-circle text-success"></i> Tejido acanalado con elasticidad de retorno que mantiene la forma tras múltiples usos.</li>
                            <li><i class="bi bi-check2-circle text-success"></i> Peso medio (220 g/m²) ideal para blusas, bodys y básicos premium.</li>
                            <li><i class="bi bi-check2-circle text-success"></i> Acabado suave al tacto gracias al algodón peinado y una mínima mezcla de spandex.</li>
                            <li><i class="bi bi-check2-circle text-success"></i> Tintes reactivos que resisten lavados industriales sin migración de color.</li>
                        </ul>
                    </div>
                    <div class="accordion mt-4" id="panelFicha">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFicha">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFicha" aria-expanded="false" aria-controls="collapseFicha">
                                    Ver ficha técnica y recomendaciones logísticas
                                </button>
                            </h2>
                            <div id="collapseFicha" class="accordion-collapse collapse" aria-labelledby="headingFicha" data-bs-parent="#panelFicha">
                                <div class="accordion-body">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <h6 class="text-muted mb-1">Composición sugerida</h6>
                                            <p class="mb-0">96% algodón peinado - 4% spandex.</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <h6 class="text-muted mb-1">Ancho utilizable</h6>
                                            <p class="mb-0">1,60 m en rollos industriales.</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <h6 class="text-muted mb-1">Presentación mayorista</h6>
                                            <p class="mb-0">Rollos termo sellados con ficha de trazabilidad.</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <h6 class="text-muted mb-1">Sugerencia de corte</h6>
                                            <p class="mb-0">Cortar a 1,1 m para blusas estándar y optimizar desperdicio.</p>
                                        </div>
                                    </div>
                                    <hr>
                                    <p class="mb-0 small text-muted">¿Atendiendo un pedido corporativo? Combina rollos y metros para equilibrar margen y disponibilidad sin frenar tu línea de producción.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="detail-card h-100">
                    <form action="<?= BASE_URL ?>/controllers/pedidos_cliente.php" method="POST" id="form-reserva" class="d-flex flex-column h-100">
                        <input type="hidden" name="producto_id" value="<?= (int) $producto['id']; ?>">
                        <input type="hidden" name="color_id" id="color_id" value="<?= (int) ($colorDisponible ?? 0); ?>">
                        <input type="hidden" name="presentacion_id" id="presentacion_id" value="<?= (int) ($presentacionDisponible ?? 0); ?>">
                        <div class="mb-4">
                            <h5 class="fw-semibold mb-3">Selecciona un color</h5>
                            <?php if (!empty($colores)): ?>
                                <div class="d-flex flex-wrap gap-3" id="grupo-colores">
                                    <?php foreach ($colores as $color): ?>
                                        <?php
                                            $activo = (int) $color['id'] === (int) ($colorDisponible ?? $color['id']);
                                            $codigo = $color['codigo_hex'] ?? '#cccccc';
                                        ?>
                                        <button type="button" class="color-chip <?= $activo ? 'active' : ''; ?>" data-color-id="<?= (int) $color['id']; ?>" style="--color-chip: <?= htmlspecialchars($codigo, ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?= htmlspecialchars($color['nombre'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <span class="color-chip-dot" style="background: <?= htmlspecialchars($codigo, ENT_QUOTES, 'UTF-8'); ?>"></span>
                                            <span class="color-chip-label"><?= htmlspecialchars($color['nombre'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted mb-0">Aún no se configuraron colores para este producto.</p>
                            <?php endif; ?>
                        </div>
                        <div class="mb-4">
                            <h5 class="fw-semibold mb-3">Tipo de compra</h5>
                            <?php if (!empty($presentaciones)): ?>
                                <div class="row g-3" id="grupo-presentaciones">
                                    <?php foreach ($presentaciones as $presentacion): ?>
                                        <?php $activo = (int) $presentacion['id'] === (int) ($presentacionDisponible ?? $presentacion['id']); ?>
                                        <div class="col-sm-6">
                                            <label class="option-card <?= $activo ? 'active' : ''; ?>" data-presentacion-id="<?= (int) $presentacion['id']; ?>" data-tipo="<?= htmlspecialchars($presentacion['tipo'], ENT_QUOTES, 'UTF-8'); ?>" data-precio="<?= number_format((float) $presentacion['precio'], 2, '.', ''); ?>" data-metros="<?= number_format((float) ($presentacion['metros_por_unidad'] ?? 0), 2, '.', ''); ?>">
                                                <strong class="d-block text-capitalize mb-1"><?= htmlspecialchars($presentacion['tipo'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                                <span class="d-block text-muted small">Bs <?= number_format((float) $presentacion['precio'], 2); ?> <?= $presentacion['tipo'] === 'rollo' ? 'por rollo' : 'por metro'; ?></span>
                                                <?php if ($presentacion['tipo'] === 'rollo'): ?>
                                                    <span class="badge-soft mt-2">≈ <?= number_format((float) ($presentacion['metros_por_unidad'] ?? 0), 0); ?> metros útiles</span>
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted mb-0">Todavía no se cargaron las presentaciones comerciales.</p>
                            <?php endif; ?>
                        </div>
                        <div class="mb-4">
                            <h5 class="fw-semibold mb-3">Cantidad</h5>
                            <div class="input-group">
                                <label class="input-group-text" for="cantidad">Unidades</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" min="0.5" step="0.5" value="1" required>
                            </div>
                            <div class="form-text" id="ayuda-disponibilidad"></div>
                        </div>
                        <div class="mt-auto">
                            <div class="summary-card mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-muted">Precio unitario</span>
                                    <strong id="precio-unitario">Bs 0.00</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Subtotal estimado</span>
                                    <strong id="total-resumen">Bs 0.00</strong>
                                </div>
                                <div class="small text-muted mt-2" id="equivalencia-texto"></div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" id="btn-reservar" <?= empty($variantes) ? 'disabled' : ''; ?>>Reservar selección</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <section class="client-section mt-5">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="detail-card h-100">
                        <h5 class="fw-semibold">Ideas de negocio con Morley y Baby Rib</h5>
                        <p class="text-muted">Estas bases elásticas son las más buscadas para colecciones cápsula de fast fashion y uniformes corporativos con tacto confortable.</p>
                        <ul class="list-unstyled mb-0">
                            <li><i class="bi bi-lightning-charge text-warning"></i> Produce drops semanales combinando colores neutros y acentos intensos sin cambiar de proveedor.</li>
                            <li><i class="bi bi-bag-check text-primary"></i> Ofrece kits a diseñadores independientes mezclando rollos y cortes por metro según su calendario de lanzamientos.</li>
                            <li><i class="bi bi-truck text-success"></i> Planifica reposiciones por color con mínimo de 3 rollos para optimizar flete internacional y mantener margen.</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-card h-100">
                        <h5 class="fw-semibold">Servicio Camila Textil</h5>
                        <p class="text-muted">Centralizamos las importaciones y el picking local para que tu equipo comercial se concentre en vender.</p>
                        <div class="d-flex flex-column gap-2">
                            <span class="badge-soft">Control de lote y trazabilidad</span>
                            <span class="badge-soft">Entrega en 24 h en Lima Metropolitana</span>
                            <span class="badge-soft">Financiación para clientes recurrentes</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<script>
    const dataVariantes = <?= json_encode($datosVariantes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
    const colorInput = document.getElementById('color_id');
    const presentacionInput = document.getElementById('presentacion_id');
    const cantidadInput = document.getElementById('cantidad');
    const precioUnitario = document.getElementById('precio-unitario');
    const totalResumen = document.getElementById('total-resumen');
    const textoEquivalencia = document.getElementById('equivalencia-texto');
    const ayudaDisponibilidad = document.getElementById('ayuda-disponibilidad');
    const botonReservar = document.getElementById('btn-reservar');

    const formatoMoneda = new Intl.NumberFormat('es-BO', { style: 'currency', currency: 'BOB' });
    const formatoCantidad = new Intl.NumberFormat('es-BO', { maximumFractionDigits: 2, minimumFractionDigits: 0 });

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
            precioUnitario.textContent = 'Bs 0.00';
            totalResumen.textContent = 'Bs 0.00';
            textoEquivalencia.textContent = '';
            ayudaDisponibilidad.textContent = 'Selecciona una combinación con stock disponible.';
            botonReservar.disabled = true;
            return;
        }

        const stock = parseFloat(detalle.stock);
        const tipo = detalle.tipo;
        const metros = tipo === 'rollo' ? Math.round(detalle.metrosPorUnidad) : 1;
        const precio = detalle.precio;

        let cantidadMaxima = 0;

        if (tipo === 'rollo') {
            cantidadInput.step = '1';
            cantidadInput.min = '1';
            cantidadMaxima = Math.floor(stock);
            const valorActual = parseFloat(cantidadInput.value);
            if (valorActual < 1 || Number.isNaN(valorActual)) {
                cantidadInput.value = 1;
            } else {
                cantidadInput.value = Math.round(valorActual);
            }
        } else {
            cantidadInput.step = '0.5';
            cantidadInput.min = '0.5';
            cantidadMaxima = stock;
            if (parseFloat(cantidadInput.value) < 0.5) {
                cantidadInput.value = 0.5;
            }
        }

        if (cantidadMaxima > 0) {
            cantidadInput.max = cantidadMaxima;
        } else {
            cantidadInput.removeAttribute('max');
        }

        const cantidadSeleccionada = parseFloat(cantidadInput.value) || 0;

        if (cantidadMaxima > 0 && cantidadSeleccionada > cantidadMaxima) {
            cantidadInput.value = cantidadMaxima;
        }

        precioUnitario.textContent = formatoMoneda.format(precio);
        const cantidad = parseFloat(cantidadInput.value) || 0;
        totalResumen.textContent = formatoMoneda.format(precio * cantidad);

        if (stock <= 0) {
            ayudaDisponibilidad.textContent = 'Sin stock para esta combinación, prueba con otro color o presentación.';
            botonReservar.disabled = true;
        } else {
            const equivalenciaTexto = tipo === 'rollo'
                ? `Cada rollo equivale aproximadamente a ${metros} metros.`
                : 'Puedes solicitar cortes desde 0.5 metros en adelante.';
            textoEquivalencia.textContent = equivalenciaTexto;
            const disponibleTexto = tipo === 'rollo'
                ? `${cantidadMaxima} ${cantidadMaxima === 1 ? 'rollo' : 'rollos'}`
                : `${formatoCantidad.format(stock)} metros`;
            ayudaDisponibilidad.textContent = `Disponibles: ${disponibleTexto}.`;
            botonReservar.disabled = cantidad <= 0;
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
