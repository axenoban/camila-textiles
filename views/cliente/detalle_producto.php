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

$disponibleProducto = (float) ($producto['stock_total'] ?? $producto['stock'] ?? 0) > 0;
$estadoProducto = $disponibleProducto ? 'Disponible' : 'Agotado temporalmente';
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

    .feature-box {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 0.75rem;
        padding: 1.25rem;
        background: rgba(13, 110, 253, 0.03);
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
                    <a class="btn btn-link px-0" href="<?= BASE_URL ?>/views/cliente/productos.php"><i class="bi bi-arrow-left"></i> Volver al catálogo</a>
                    <h1 class="section-title mb-1"><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?></h1>
                    <p class="text-muted mb-0">Analiza variantes, arma tu pedido y reserva en segundos con la misma experiencia del catálogo público.</p>
                </div>
                <div class="text-lg-end">
                    <span class="badge bg-light text-primary fw-semibold">Precio desde Bs <?= number_format((float) ($producto['precio_desde'] ?? $producto['precio']), 2); ?></span>
                    <div class="small text-muted">Estado: <?= htmlspecialchars($estadoProducto, ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
            </div>

            <?php if ($mensajeReserva) { ?>
                <div class="alert alert-<?= $tipoReserva === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert" data-auto-dismiss="true">
                    <?= htmlspecialchars($mensajeReserva, ENT_QUOTES, 'UTF-8'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            <?php } ?>

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
                                                <p class="mb-0">Corta a 1,1 m para blusas estándar y optimiza desperdicio.</p>
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
                            <input type="hidden" name="line_items" id="line_items" value="[]">

                            <div class="mb-4">
                                <h5 class="fw-semibold mb-3">Colores disponibles</h5>
                                <?php if (!empty($colores)) { ?>
                                    <div class="d-flex flex-wrap gap-3" id="grupo-colores">
                                        <?php foreach ($colores as $color) {
                                            $activo = (int) $color['id'] === (int) ($colorDisponible ?? $color['id']);
                                            $codigo = $color['codigo_hex'] ?? '#cccccc';
                                        ?>
                                            <button type="button" class="color-chip <?= $activo ? 'active' : ''; ?>" data-color-id="<?= (int) $color['id']; ?>" aria-label="<?= htmlspecialchars($color['nombre'], ENT_QUOTES, 'UTF-8'); ?>">
                                                <span class="color-chip-dot" style="background: <?= htmlspecialchars($codigo, ENT_QUOTES, 'UTF-8'); ?>"></span>
                                                <span><?= htmlspecialchars($color['nombre'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            </button>
                                        <?php } ?>
                                    </div>
                                <?php } else { ?>
                                    <p class="text-muted mb-0">Pronto añadiremos la paleta completa de este textil.</p>
                                <?php } ?>
                            </div>

                            <div class="mb-4">
                                <h5 class="fw-semibold mb-3">Presentaciones de venta</h5>
                                <?php if (!empty($presentaciones)) { ?>
                                    <div class="row g-3" id="grupo-presentaciones">
                                        <?php foreach ($presentaciones as $presentacion) {
                                            $activo = (int) $presentacion['id'] === (int) ($presentacionDisponible ?? $presentacion['id']);
                                        ?>
                                            <div class="col-sm-6">
                                                <div class="option-card <?= $activo ? 'active' : ''; ?>" data-presentacion-id="<?= (int) $presentacion['id']; ?>" data-tipo="<?= htmlspecialchars($presentacion['tipo'], ENT_QUOTES, 'UTF-8'); ?>" data-precio="<?= number_format((float) $presentacion['precio'], 2, '.', ''); ?>" data-metros="<?= number_format((float) ($presentacion['metros_por_unidad'] ?? 0), 2, '.', ''); ?>">
                                                    <strong class="d-block text-capitalize mb-1"><?= htmlspecialchars($presentacion['tipo'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                                    <span class="d-block text-muted small">Bs <?= number_format((float) $presentacion['precio'], 2); ?> <?= $presentacion['tipo'] === 'rollo' ? 'por rollo' : 'por metro'; ?></span>
                                                    <?php if ($presentacion['tipo'] === 'rollo') { ?>
                                                        <span class="badge-soft mt-2">≈ <?= number_format((float) ($presentacion['metros_por_unidad'] ?? 0), 0); ?> metros por rollo</span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } else { ?>
                                    <p class="text-muted mb-0">Pronto añadiremos las presentaciones comerciales.</p>
                                <?php } ?>
                            </div>

                            <div class="mb-4">
                                <h5 class="fw-semibold mb-3">Cantidad</h5>
                                <div class="input-group">
                                    <label class="input-group-text" for="cantidad">Unidades</label>
                                    <input type="number" class="form-control" id="cantidad" min="0.5" step="0.5" value="0.5" required>
                                </div>
                                <div class="form-text" id="ayuda-disponibilidad"></div>
                            </div>

                            <div class="mb-4">
                                <button type="button" class="btn btn-outline-primary w-100" id="btn-agregar" <?= empty($variantes) ? 'disabled' : ''; ?>>Agregar combinación al pedido</button>
                            </div>

                            <div class="mb-4">
                                <h5 class="fw-semibold mb-3">Resumen del pedido</h5>
                                <div id="resumen-items" class="summary-card">
                                    <p class="mb-0 text-muted">Todavía no has agregado combinaciones. Selecciona color, presentación y cantidad para construir tu pedido.</p>
                                </div>
                            </div>

                            <div class="mt-auto">
                                <div class="summary-card mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-muted">Precio unitario actual</span>
                                        <strong id="precio-unitario">Bs 0.00</strong>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">Subtotal actual</span>
                                        <strong id="total-resumen">Bs 0.00</strong>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Total del pedido</span>
                                        <strong id="total-pedido">Bs 0.00</strong>
                                    </div>
                                    <div class="small text-muted mt-2" id="equivalencia-texto"></div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100" id="btn-reservar" disabled>Reservar pedido</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-2">
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
        </div>
    </section>
</main>

<script>
    const dataVariantes = <?= json_encode($datosVariantes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
    const colorInput = document.getElementById('color_id');
    const presentacionInput = document.getElementById('presentacion_id');
    const cantidadInput = document.getElementById('cantidad');
    const precioUnitarioEl = document.getElementById('precio-unitario');
    const subtotalActualEl = document.getElementById('total-resumen');
    const totalPedidoEl = document.getElementById('total-pedido');
    const equivalenciaTextoEl = document.getElementById('equivalencia-texto');
    const ayudaDisponibilidad = document.getElementById('ayuda-disponibilidad');
    const botonReservar = document.getElementById('btn-reservar');
    const botonAgregar = document.getElementById('btn-agregar');
    const resumenItems = document.getElementById('resumen-items');
    const lineItemsInput = document.getElementById('line_items');

    const formatoMoneda = new Intl.NumberFormat('es-BO', { style: 'currency', currency: 'BOB' });
    const formatoCantidad = new Intl.NumberFormat('es-BO', { maximumFractionDigits: 2, minimumFractionDigits: 0 });

    const lineItems = [];

    function obtenerDetalleSeleccion(colorId, presentacionId) {
        if (!dataVariantes.matriz[colorId] || !dataVariantes.matriz[colorId][presentacionId]) {
            return null;
        }

        const presentacion = dataVariantes.presentaciones.find(
            (p) => parseInt(p.id, 10) === parseInt(presentacionId, 10)
        );

        return {
            ...dataVariantes.matriz[colorId][presentacionId],
            precio: presentacion ? parseFloat(presentacion.precio) : 0,
            tipo: presentacion ? presentacion.tipo : 'metro',
            metrosPorUnidad: presentacion ? parseFloat(presentacion.metros_por_unidad || 1) : 1,
        };
    }

    function obtenerCantidadEnPedido(colorId, presentacionId) {
        const encontrado = lineItems.find(
            (item) => item.color_id === colorId && item.presentacion_id === presentacionId
        );

        return encontrado ? parseFloat(encontrado.cantidad) : 0;
    }

    function renderResumen() {
        if (!resumenItems) {
            return;
        }

        if (lineItems.length === 0) {
            resumenItems.innerHTML = '<p class="mb-0 text-muted">Todavía no has agregado combinaciones. Selecciona color, presentación y cantidad para construir tu pedido.</p>';
            totalPedidoEl.textContent = formatoMoneda.format(0);
            botonReservar.disabled = true;
            lineItemsInput.value = '[]';
            return;
        }

        let total = 0;
        let contenido = '<div class="table-responsive"><table class="table table-modern table-sm mb-0">';
        contenido += '<thead><tr><th>Color</th><th>Presentación</th><th>Cantidad</th><th>Precio unitario</th><th>Subtotal</th><th class="text-end">Acciones</th></tr></thead><tbody>';

        lineItems.forEach((item, index) => {
            const subtotal = item.precio_unitario * item.cantidad;
            total += subtotal;
            contenido += `<tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge rounded-pill" style="background:${item.codigo_hex}; width: 1.5rem; height: 1.5rem;"></span>
                        <span>${item.color_nombre}</span>
                    </div>
                </td>
                <td class="text-capitalize">${item.tipo}</td>
                <td>${formatoCantidad.format(item.cantidad)}</td>
                <td>${formatoMoneda.format(item.precio_unitario)}</td>
                <td>${formatoMoneda.format(subtotal)}</td>
                <td class="text-end"><button type="button" class="btn btn-link text-danger p-0" data-index="${index}"><i class="bi bi-x-circle"></i></button></td>
            </tr>`;
        });

        contenido += '</tbody></table></div>';
        resumenItems.innerHTML = contenido;
        totalPedidoEl.textContent = formatoMoneda.format(total);
        botonReservar.disabled = false;
        lineItemsInput.value = JSON.stringify(lineItems);

        resumenItems.querySelectorAll('button[data-index]').forEach((btn) => {
            btn.addEventListener('click', (event) => {
                const idx = parseInt(event.currentTarget.getAttribute('data-index'), 10);
                if (!Number.isNaN(idx)) {
                    lineItems.splice(idx, 1);
                    renderResumen();
                }
            });
        });
    }

    function actualizarAyuda(colorId, presentacionId) {
        if (!ayudaDisponibilidad) {
            return;
        }

        const detalle = obtenerDetalleSeleccion(colorId, presentacionId);
        if (!detalle) {
            ayudaDisponibilidad.textContent = 'Selecciona una combinación para revisar disponibilidad.';
            return;
        }

        const disponible = parseFloat(detalle.stock || 0);
        const tipo = detalle.tipo === 'rollo' ? 'rollos' : 'metros';
        ayudaDisponibilidad.textContent = disponible > 0
            ? `Disponibilidad estimada: ${formatoCantidad.format(disponible)} ${tipo}.`
            : 'Combinación agotada temporalmente.';
    }

    function actualizarPrecioResumen(colorId, presentacionId) {
        const detalle = obtenerDetalleSeleccion(colorId, presentacionId);
        if (!detalle) {
            precioUnitarioEl.textContent = 'Bs 0.00';
            subtotalActualEl.textContent = 'Bs 0.00';
            equivalenciaTextoEl.textContent = '';
            return;
        }

        const cantidad = parseFloat(cantidadInput.value || 0);
        const precioUnitario = detalle.precio;
        const subtotal = precioUnitario * cantidad;
        precioUnitarioEl.textContent = formatoMoneda.format(precioUnitario);
        subtotalActualEl.textContent = formatoMoneda.format(subtotal);

        if (detalle.tipo === 'rollo') {
            const metrosTotales = cantidad * detalle.metrosPorUnidad;
            equivalenciaTextoEl.textContent = `Equivalente aproximado: ${formatoCantidad.format(metrosTotales)} metros.`;
        } else {
            equivalenciaTextoEl.textContent = '';
        }
    }

    function seleccionarColor(colorId) {
        const botones = document.querySelectorAll('#grupo-colores .color-chip');
        botones.forEach((btn) => {
            const coincide = parseInt(btn.getAttribute('data-color-id'), 10) === parseInt(colorId, 10);
            btn.classList.toggle('active', coincide);
        });
        colorInput.value = colorId;
        actualizarAyuda(parseInt(colorId, 10), parseInt(presentacionInput.value || 0, 10));
        actualizarPrecioResumen(parseInt(colorId, 10), parseInt(presentacionInput.value || 0, 10));
    }

    function seleccionarPresentacion(presentacionId) {
        const tarjetas = document.querySelectorAll('#grupo-presentaciones .option-card');
        tarjetas.forEach((card) => {
            const coincide = parseInt(card.getAttribute('data-presentacion-id'), 10) === parseInt(presentacionId, 10);
            card.classList.toggle('active', coincide);
        });
        presentacionInput.value = presentacionId;
        actualizarAyuda(parseInt(colorInput.value || 0, 10), parseInt(presentacionId, 10));
        actualizarPrecioResumen(parseInt(colorInput.value || 0, 10), parseInt(presentacionId, 10));
    }

    document.querySelectorAll('#grupo-colores .color-chip').forEach((btn) => {
        btn.addEventListener('click', () => {
            seleccionarColor(btn.getAttribute('data-color-id'));
        });
    });

    document.querySelectorAll('#grupo-presentaciones .option-card').forEach((card) => {
        card.addEventListener('click', () => {
            seleccionarPresentacion(card.getAttribute('data-presentacion-id'));
        });
    });

    cantidadInput.addEventListener('input', () => {
        actualizarPrecioResumen(parseInt(colorInput.value || 0, 10), parseInt(presentacionInput.value || 0, 10));
    });

    botonAgregar?.addEventListener('click', () => {
        const colorId = parseInt(colorInput.value || 0, 10);
        const presentacionId = parseInt(presentacionInput.value || 0, 10);
        const cantidad = parseFloat(cantidadInput.value || 0);
        const detalle = obtenerDetalleSeleccion(colorId, presentacionId);

        if (!detalle || Number.isNaN(cantidad) || cantidad <= 0) {
            return;
        }

        const disponible = parseFloat(detalle.stock || 0);
        const acumulado = obtenerCantidadEnPedido(colorId, presentacionId);
        const totalDeseado = acumulado + cantidad;

        if (detalle.tipo === 'rollo' && !Number.isInteger(totalDeseado)) {
            return;
        }

        if (totalDeseado > disponible) {
            ayudaDisponibilidad.textContent = 'La cantidad supera la disponibilidad estimada para esta combinación.';
            return;
        }

        const existente = lineItems.findIndex(
            (item) => item.color_id === colorId && item.presentacion_id === presentacionId
        );

        const payload = {
            color_id: colorId,
            presentacion_id: presentacionId,
            cantidad,
            precio_unitario: detalle.precio,
            tipo: detalle.tipo,
            metros_por_unidad: detalle.metrosPorUnidad,
            codigo_hex: detalle.codigoHex || '#d1d5db',
            color_nombre: detalle.colorNombre,
        };

        if (existente >= 0) {
            lineItems[existente].cantidad += cantidad;
        } else {
            lineItems.push(payload);
        }

        renderResumen();
        actualizarPrecioResumen(colorId, presentacionId);
    });

    seleccionarColor(colorInput.value || 0);
    seleccionarPresentacion(presentacionInput.value || 0);
    actualizarAyuda(parseInt(colorInput.value || 0, 10), parseInt(presentacionInput.value || 0, 10));
    actualizarPrecioResumen(parseInt(colorInput.value || 0, 10), parseInt(presentacionInput.value || 0, 10));

    const autoDismissAlerts = document.querySelectorAll('[data-auto-dismiss="true"]');
    autoDismissAlerts.forEach((alerta) => {
        setTimeout(() => {
            const alertaBootstrap = bootstrap.Alert.getOrCreateInstance(alerta);
            alertaBootstrap.close();
        }, 3000);
    });
</script>

<?php include('includes/footer.php'); ?>
