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
                        <input type="hidden" name="line_items" id="line_items" value="[]">
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

            const cantidadLegible = item.presentacion_tipo === 'rollo'
                ? `${item.cantidad} ${item.cantidad === 1 ? 'rollo' : 'rollos'}${item.metros_por_unidad ? ` · ${Math.round(item.metros_por_unidad)} m c/u` : ''}`
                : `${formatoCantidad.format(item.cantidad)} metros`;

            contenido += `
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-pill" style="background:${item.codigo_hex || '#d1d5db'}; width:1.5rem; height:1.5rem;"></span>
                            <span>${item.color_nombre}</span>
                        </div>
                    </td>
                    <td class="text-capitalize">${item.presentacion_tipo}</td>
                    <td>${cantidadLegible}</td>
                    <td>${formatoMoneda.format(item.precio_unitario)}</td>
                    <td>${formatoMoneda.format(subtotal)}</td>
                    <td class="text-end">
                        <button type="button" class="btn btn-link btn-sm text-danger" data-remove-index="${index}">Quitar</button>
                    </td>
                </tr>
            `;
        });

        contenido += '</tbody></table></div>';
        resumenItems.innerHTML = contenido;
        totalPedidoEl.textContent = formatoMoneda.format(total);
        botonReservar.disabled = false;
        lineItemsInput.value = JSON.stringify(lineItems.map((item) => ({
            color_id: item.color_id,
            presentacion_id: item.presentacion_id,
            cantidad: item.cantidad,
        })));

        resumenItems.querySelectorAll('[data-remove-index]').forEach((boton) => {
            boton.addEventListener('click', () => {
                const indice = parseInt(boton.dataset.removeIndex, 10);
                if (!Number.isNaN(indice)) {
                    lineItems.splice(indice, 1);
                    renderResumen();
                    actualizarEstado();
                }
            });
        });
    }

    function actualizarEstado() {
        const colorId = parseInt(colorInput.value, 10);
        const presentacionId = parseInt(presentacionInput.value, 10);
        const detalle = obtenerDetalleSeleccion(colorId, presentacionId);

        if (!detalle) {
            precioUnitarioEl.textContent = 'Bs 0.00';
            subtotalActualEl.textContent = 'Bs 0.00';
            equivalenciaTextoEl.textContent = '';
            ayudaDisponibilidad.textContent = 'Selecciona una combinación con stock disponible.';
            botonAgregar.disabled = true;
            return;
        }

        const stock = parseFloat(detalle.stock);
        const reservado = obtenerCantidadEnPedido(colorId, presentacionId);
        const disponible = Math.max(0, stock - reservado);
        const tipo = detalle.tipo;
        const precio = detalle.precio;
        const metros = tipo === 'rollo' ? Math.round(detalle.metrosPorUnidad || 0) : 1;

        if (tipo === 'rollo') {
            cantidadInput.step = '1';
            cantidadInput.min = '1';
            const valorActual = parseFloat(cantidadInput.value);
            if (Number.isNaN(valorActual) || valorActual < 1) {
                cantidadInput.value = 1;
            } else {
                cantidadInput.value = Math.round(valorActual);
            }
            cantidadInput.max = disponible > 0 ? Math.floor(disponible) : 1;
        } else {
            cantidadInput.step = '0.5';
            cantidadInput.min = '0.5';
            const valorActual = parseFloat(cantidadInput.value);
            if (Number.isNaN(valorActual) || valorActual < 0.5) {
                cantidadInput.value = 0.5;
            } else {
                cantidadInput.value = (Math.round(valorActual * 2) / 2).toFixed(1);
            }
            if (disponible > 0) {
                cantidadInput.max = disponible;
            } else {
                cantidadInput.removeAttribute('max');
            }
        }

        const cantidad = parseFloat(cantidadInput.value) || 0;

        precioUnitarioEl.textContent = formatoMoneda.format(precio);
        subtotalActualEl.textContent = formatoMoneda.format(precio * cantidad);

        if (stock <= 0) {
            ayudaDisponibilidad.textContent = 'Sin stock para esta combinación, prueba con otro color o presentación.';
            botonAgregar.disabled = true;
            return;
        }

        const equivalenciaTexto = tipo === 'rollo'
            ? `Cada rollo equivale aproximadamente a ${metros} metros.`
            : 'Puedes solicitar cortes desde 0.5 metros en adelante.';
        equivalenciaTextoEl.textContent = equivalenciaTexto;

        const disponibleTexto = tipo === 'rollo'
            ? `${Math.floor(disponible)} ${Math.floor(disponible) === 1 ? 'rollo disponible' : 'rollos disponibles'}`
            : `${formatoCantidad.format(disponible)} metros disponibles`;
        const reservadoTexto = reservado > 0
            ? (tipo === 'rollo'
                ? `${reservado} ${reservado === 1 ? 'rollo reservado' : 'rollos reservados'}`
                : `${formatoCantidad.format(reservado)} metros reservados`)
            : null;

        ayudaDisponibilidad.textContent = reservadoTexto
            ? `${disponibleTexto} · ${reservadoTexto} en este pedido.`
            : `${disponibleTexto}.`;

        botonAgregar.disabled = disponible <= 0;
    }

    function agregarCombinacion() {
        const colorId = parseInt(colorInput.value, 10);
        const presentacionId = parseInt(presentacionInput.value, 10);
        const detalle = obtenerDetalleSeleccion(colorId, presentacionId);

        if (!detalle) {
            return;
        }

        let cantidad = parseFloat(cantidadInput.value);
        if (Number.isNaN(cantidad) || cantidad <= 0) {
            ayudaDisponibilidad.textContent = 'Ingresa una cantidad válida antes de agregar la combinación.';
            return;
        }

        if (detalle.tipo === 'rollo') {
            cantidad = Math.max(1, Math.round(cantidad));
        } else {
            cantidad = Math.max(0.5, Math.round(cantidad * 2) / 2);
        }

        const reservado = obtenerCantidadEnPedido(colorId, presentacionId);
        const stock = parseFloat(detalle.stock);
        const disponible = stock - reservado;

        if (disponible <= 0) {
            ayudaDisponibilidad.textContent = 'Ya has reservado el máximo disponible para esta variante.';
            return;
        }

        if (cantidad > disponible + 0.0001) {
            ayudaDisponibilidad.textContent = detalle.tipo === 'rollo'
                ? `Solo puedes agregar ${Math.floor(disponible)} ${Math.floor(disponible) === 1 ? 'rollo adicional' : 'rollos adicionales'}.`
                : `Solo puedes agregar ${formatoCantidad.format(disponible)} metros adicionales.`;
            return;
        }

        const existente = lineItems.findIndex(
            (item) => item.color_id === colorId && item.presentacion_id === presentacionId
        );

        if (existente >= 0) {
            lineItems[existente].cantidad = parseFloat((lineItems[existente].cantidad + cantidad).toFixed(2));
        } else {
            lineItems.push({
                color_id: colorId,
                color_nombre: detalle.colorNombre,
                codigo_hex: detalle.codigoHex,
                presentacion_id: presentacionId,
                presentacion_tipo: detalle.tipo,
                metros_por_unidad: detalle.metrosPorUnidad,
                precio_unitario: detalle.precio,
                cantidad: parseFloat(cantidad.toFixed(2)),
            });
        }

        ayudaDisponibilidad.textContent = 'Combinación agregada al resumen de pedido.';
        if (detalle.tipo === 'rollo') {
            cantidadInput.value = '1';
        } else {
            cantidadInput.value = '0.5';
        }

        renderResumen();
        actualizarEstado();
    }

    document.querySelectorAll('.color-chip').forEach((chip) => {
        chip.addEventListener('click', () => {
            document.querySelectorAll('.color-chip').forEach((c) => c.classList.remove('active'));
            chip.classList.add('active');
            colorInput.value = chip.dataset.colorId;
            actualizarEstado();
        });
    });

    document.querySelectorAll('.option-card').forEach((option) => {
        option.addEventListener('click', () => {
            document.querySelectorAll('.option-card').forEach((op) => op.classList.remove('active'));
            option.classList.add('active');
            presentacionInput.value = option.dataset.presentacionId;
            actualizarEstado();
        });
    });

    cantidadInput.addEventListener('change', actualizarEstado);
    cantidadInput.addEventListener('keyup', actualizarEstado);

    if (botonAgregar) {
        botonAgregar.addEventListener('click', agregarCombinacion);
    }

    const formulario = document.getElementById('form-reserva');
    if (formulario) {
        formulario.addEventListener('submit', (evento) => {
            if (lineItems.length === 0) {
                evento.preventDefault();
                ayudaDisponibilidad.textContent = 'Añade al menos una combinación para enviar tu pedido.';
            } else {
                lineItemsInput.value = JSON.stringify(lineItems.map((item) => ({
                    color_id: item.color_id,
                    presentacion_id: item.presentacion_id,
                    cantidad: item.cantidad,
                })));
            }
        });
    }

    renderResumen();
    actualizarEstado();
</script>

<?php include('includes/footer.php'); ?>
