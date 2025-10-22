<?php
require_once __DIR__ . '/../../models/producto.php';

$productoModel = new Producto();

// Obtener estado desde el parámetro GET (por defecto 'activo')
$estado = $_GET['estado'] ?? 'activo';
$productos = $productoModel->obtenerProductosPorEstado($estado);
?>

<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <!-- ✅ MENSAJES DE ESTADO -->
        <?php
        $status = $_GET['status'] ?? null;
        $mensajes = [
            'creado' => ['type' => 'success', 'text' => 'El producto se añadió correctamente al catálogo.'],
            'actualizado' => ['type' => 'success', 'text' => 'El producto se actualizó correctamente.'],
            'eliminado' => ['type' => 'success', 'text' => 'El producto se eliminó del catálogo.'],
            'estado' => ['type' => 'info', 'text' => 'Se actualizó el estado del producto.'],
            'error' => ['type' => 'danger', 'text' => 'No se pudo completar la operación solicitada.'],
        ];

        if ($status && isset($mensajes[$status])): ?>
            <div class="alert alert-<?= $mensajes[$status]['type']; ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($mensajes[$status]['text']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

        <!-- 🧭 ENCABEZADO -->
        <header class="page-header text-center text-lg-start mb-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h1 class="page-title mb-2">Gestión de productos</h1>
                    <p class="page-subtitle mb-0 text-muted">
                        Controla la ficha técnica de cada tela, sus variantes de color y disponibilidad en tiempo real.
                    </p>
                </div>
                <a href="agregar_producto.php" class="btn btn-success">
                    <i class="bi bi-plus-circle me-2"></i>Agregar producto
                </a>
            </div>
        </header>

        <!-- Botón para alternar entre productos activos e inactivos -->
        <div class="mb-3 text-end">
            <a href="?estado=<?= $estado === 'activo' ? 'inactivo' : 'activo'; ?>" class="btn btn-primary">
                Mostrar productos <?= $estado === 'activo' ? 'inactivos' : 'activos'; ?>
            </a>
        </div>

        <!-- 📦 TABLA DE PRODUCTOS -->
        <div class="portal-table">
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Precio</th>
                            <th>Visibilidad</th>
                            <th>Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($productos)): ?>
                            <?php foreach ($productos as $p): ?>
                                <tr id="producto-<?= (int)$p['id']; ?>">
                                    <td><?= (int)$p['id']; ?></td>
                                    <td>
                                        <?php if (filter_var($p['imagen_principal'], FILTER_VALIDATE_URL)): ?>
                                            <img src="<?= htmlspecialchars($p['imagen_principal']); ?>" alt="<?= htmlspecialchars($p['nombre']); ?>" style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                                        <?php else: ?>
                                            <img src="<?= BASE_URL . '/uploads/' . basename($p['imagen_principal']); ?>" alt="<?= htmlspecialchars($p['nombre']); ?>" style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($p['nombre']); ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars($p['descripcion']); ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($p['tipo_tela'] ?? '—'); ?></td>
                                    <td>
                                        <span class="fw-semibold text-primary">
                                            Bs <?= number_format($p['precio_metro'], 2, ',', '.'); ?>
                                        </span><br>
                                        <small class="text-muted">
                                            Rollo: Bs <?= number_format($p['precio_rollo'], 2, ',', '.'); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php if ($p['visible']): ?>
                                            <span class="badge bg-success">Visible</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Oculto</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($p['fecha_creacion'])); ?></td>
                                    <td class="text-nowrap">
                                        <!-- Editar Producto -->
                                        <a href="editar_producto.php?id=<?= (int)$p['id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <!-- Colores del Producto -->
                                        <a href="colores_producto.php?id=<?= (int)$p['id']; ?>" class="btn btn-info btn-sm">
                                            <i class="bi bi-palette"></i>
                                        </a>

                                        <!-- Toggle Visibilidad -->
                                        <a href="toggle_visibilidad.php?id=<?= (int)$p['id']; ?>" class="btn btn-secondary btn-sm">
                                            <i class="bi bi-eye<?= $p['visible'] ? '-slash' : ''; ?>"></i>
                                        </a>


                                        <!-- Eliminar Producto -->
                                        <button class="btn btn-danger btn-sm" onclick="eliminarProducto(<?= (int)$p['id']; ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No hay productos registrados en el sistema.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- 🔹 MODAL DE CONFIRMACIÓN -->
<div class="modal fade" id="modalEliminarProducto" tabindex="-1" aria-labelledby="modalEliminarProductoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalEliminarProductoLabel"><i class="bi bi-exclamation-triangle me-2"></i>Confirmar eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p class="mb-1">¿Estás seguro de que deseas eliminar este producto?</p>
                <p class="fw-semibold text-danger mb-0" id="nombreProductoEliminar"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- 🔸 CONTENEDOR DE NOTIFICACIONES -->
<div id="notificacionContainer" class="position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 9999; width: 100%; max-width: 500px;"></div>

<script>
    let idEliminar = null;
    let filaEliminar = null;

    // Función para mostrar modal de confirmación de eliminación
function eliminarProducto(id) {
    const fila = document.getElementById("producto-" + id);
    const nombre = fila ? fila.querySelector("strong")?.innerText.trim() : "Producto sin nombre";

    idEliminar = id;
    filaEliminar = fila;
    document.getElementById("nombreProductoEliminar").innerText = nombre;

    const modal = new bootstrap.Modal(document.getElementById("modalEliminarProducto"));
    modal.show();
}

// Confirmar eliminación con AJAX
document.getElementById("btnConfirmarEliminar").addEventListener("click", function() {
    if (!idEliminar) return;

    fetch("<?= BASE_URL ?>/controllers/productos.php", {
        method: "POST", // Cambiar a POST
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            accion: "eliminar",
            id: idEliminar // Pasar el id a través de POST
        })
    })
    .then(res => res.json())
    .then(data => {
        const modal = bootstrap.Modal.getInstance(document.getElementById("modalEliminarProducto"));
        modal.hide();

        if (data.status === "success") {
            if (filaEliminar) filaEliminar.remove(); // Eliminar la fila del producto
            mostrarNotificacion("success", data.message);
        } else {
            mostrarNotificacion("danger", data.message || "⚠️ No se pudo eliminar el producto.");
        }
    })
    .catch(err => {
        console.error("Error:", err);
        mostrarNotificacion("danger", "❌ Error interno al intentar eliminar el producto.");
    });
});



    /**
     * 🧩 Función para mostrar notificaciones flotantes
     */
    function mostrarNotificacion(tipo, mensaje) {
        const contenedor = document.getElementById("notificacionContainer");

        const alerta = document.createElement("div");
        alerta.className = `alert alert-${tipo} alert-dismissible fade show shadow`;
        alerta.innerHTML = `
    <div class="d-flex align-items-center gap-2">
      <i class="bi ${tipo === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle'} fs-5"></i>
      <span>${mensaje}</span>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
  `;

        contenedor.appendChild(alerta);

        // Auto cerrar en 4 segundos
        setTimeout(() => {
            alerta.classList.remove("show");
            alerta.classList.add("fade");
            setTimeout(() => alerta.remove(), 300);
        }, 4000);
    }
</script>

<?php include('includes/footer.php'); ?>