<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../models/pedido.php';

$pedidoModel = new Pedido();
$pedidos = $pedidoModel->obtenerPedidosAgrupados();
?>

<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <header class="page-header text-center text-lg-start mb-4">
            <h1 class="page-title mb-2">Gestión de pedidos</h1>
            <p class="page-subtitle text-muted">
                Administra los pedidos agrupados por cliente y producto.
            </p>
        </header>

        <div class="table-shell">
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Cliente</th>
                            <th>Producto</th>
                            <th>Colores</th>
                            <th>Unidad</th>
                            <th>Cantidad total</th>
                            <th>Total (Bs)</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pedidos)): ?>
                            <?php foreach ($pedidos as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['cliente']); ?></td>
                                    <td><?= htmlspecialchars($p['producto']); ?></td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-2">
                                            <?php
                                            $colores = explode(', ', $p['colores']);
                                            $hex = explode(',', $p['codigos_hex']);
                                            $codigos = explode(',', $p['codigos_color'] ?? '');
                                            ?>
                                            <?php foreach ($colores as $i => $color): ?>
                                                <div class="d-flex align-items-center gap-1">
                                                    <span style="width:18px;height:18px;border-radius:50%;
                    background-color:<?= htmlspecialchars($hex[$i] ?? '#ccc'); ?>;
                    border:1px solid #aaa;"></span>
                                                    <span class="fw-semibold"><?= htmlspecialchars($codigos[$i] ?? '-'); ?></span>
                                                    <small class="text-muted"><?= htmlspecialchars($color); ?></small>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </td>

                                    <td><?= ucfirst($p['unidad']); ?></td>
                                    <td><?= number_format($p['cantidad_total'], 2); ?></td>
                                    <td><strong><?= number_format($p['total_pedido'], 2); ?></strong></td>
                                    <td>
                                        <?php
                                        $badge = match ($p['estado']) {
                                            'pendiente' => 'bg-warning text-dark',
                                            'confirmado' => 'bg-primary',
                                            'completado' => 'bg-success',
                                            'cancelado' => 'bg-secondary',
                                            default => 'bg-light text-dark'
                                        };
                                        ?>
                                        <span class="badge <?= $badge; ?>"><?= ucfirst($p['estado']); ?></span>
                                    </td>

                                    <td><?= date('d/m/Y H:i', strtotime($p['fecha_creacion'])); ?></td>
                                    <td class="text-end">
                                        <a href="<?= BASE_URL ?>/views/admin/ver_detalle_pedido.php?id=<?= (int)$p['id_pedido']; ?>"
                                            class="btn btn-outline-secondary btn-sm me-1">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <?php if ($p['estado'] === 'pendiente'): ?>
                                            <a href="<?= BASE_URL ?>/views/admin/confirmar_pedido.php?id=<?= (int)$p['id_pedido']; ?>" class="btn btn-success btn-sm"><i class="bi bi-check2-circle"></i></a>
                                            <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="eliminarPedido(<?= (int)$p['id_pedido']; ?>, '<?= htmlspecialchars($p['producto']); ?>')">
                                                <i class="bi bi-x-circle"></i> Cancelar
                                            </a>

                                        <?php elseif ($p['estado'] === 'confirmado'): ?>
                                            <a href="<?= BASE_URL ?>/views/admin/completar_pedido.php?id=<?= (int)$p['id_pedido']; ?>" class="btn btn-info btn-sm"><i class="bi bi-box-seam"></i></a>
                                        <?php else: ?>
                                            <span class="text-muted small">Sin acciones</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">No hay pedidos registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para confirmar cancelación de pedido -->
    <div class="modal fade" id="modalCancelarPedido" tabindex="-1" aria-labelledby="modalCancelarPedidoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalCancelarPedidoLabel"><i class="bi bi-exclamation-triangle me-2"></i>Confirmar cancelación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-1">¿Estás seguro de que deseas cancelar este pedido?</p>
                    <p class="fw-semibold text-danger mb-0" id="nombrePedidoCancelar"></p>

                    <!-- Motivo de cancelación -->
                    <div class="mt-3">
                        <label for="motivoCancelacion" class="form-label">Motivo de cancelación</label>
                        <select class="form-select" id="motivoCancelacion">
                            <option value="">Seleccionar motivo...</option>
                            <option value="No hay stock">No hay stock</option>
                            <option value="Error del cliente">Error del cliente</option>
                            <option value="Otro">Otro</option>
                        </select>
                        <textarea id="motivoOtro" class="form-control mt-2" placeholder="Especifica el motivo..." rows="3" style="display:none;"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarCancelar">Cancelar pedido</button>
                </div>
            </div>
        </div>
    </div>

</main>
<script>
    let idEliminar = null;

    /**
     * 🔸 Mostrar modal de confirmación
     */
    function eliminarPedido(idPedido, nombrePedido) {
        idEliminar = idPedido;
        // Actualiza el nombre del pedido en el modal
        document.getElementById("nombrePedidoCancelar").textContent = nombrePedido;
        const modal = new bootstrap.Modal(document.getElementById("modalCancelarPedido"));
        modal.show();
    }

    /**
     * 🔸 Confirmar cancelación con AJAX
     */
    document.getElementById("btnConfirmarCancelar").addEventListener("click", function() {
        if (!idEliminar) return;

        const motivoCancelacion = document.getElementById("motivoCancelacion").value;
        let motivoOtro = "";
        if (motivoCancelacion === "Otro") {
            motivoOtro = document.getElementById("motivoOtro").value;
            if (!motivoOtro.trim()) {
                alert("Por favor, especifica el motivo.");
                return;
            }
        }

        console.log('Enviando cancelación:', {
            id_pedido: idEliminar,
            motivo_cancelacion: motivoCancelacion === "Otro" ? motivoOtro : motivoCancelacion
        });

        fetch("<?= BASE_URL ?>/controllers/pedidos_admin.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({
                accion: "cancelar",
                id_pedido: idEliminar,
                motivo_cancelacion: motivoCancelacion === "Otro" ? motivoOtro : motivoCancelacion
            })
        })
        .then(res => res.json())
        .then(data => {
            const modal = bootstrap.Modal.getInstance(document.getElementById("modalCancelarPedido"));
            modal.hide();

            if (data.status === "success") {
                location.reload(); // Recargamos la página para reflejar la cancelación
                mostrarNotificacion("success", data.message);
            } else {
                mostrarNotificacion("danger", data.message || "⚠️ No se pudo cancelar el pedido.");
            }
        })
        .catch(err => {
            console.error("Error:", err);
            mostrarNotificacion("danger", "❌ Error interno al intentar cancelar el pedido.");
        });
    });
</script>



<style>
    .table .d-flex.flex-wrap.gap-2 {
        max-width: 260px;
    }

    .table .d-flex.flex-wrap.gap-2 small {
        font-size: 0.8rem;
        color: #444;
    }
</style>

<?php include('includes/footer.php'); ?>