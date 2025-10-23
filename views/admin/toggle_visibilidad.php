<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../models/producto.php';

$productoModel = new Producto();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: ' . BASE_URL . '/views/admin/productos.php?status=error');
    exit;
}

try {
    // 🧾 Obtener el producto actual
    $producto = $productoModel->obtenerProductoPorId($id);

    if (!$producto) {
        header('Location: ' . BASE_URL . '/views/admin/productos.php?status=no_encontrado');
        exit;
    }

    // 👁 Cambiar el valor de visibilidad
    $nuevoEstado = $producto['visible'] ? 0 : 1;

    $resultado = $productoModel->actualizarVisibilidad($id, $nuevoEstado);

    if ($resultado) {
        header('Location: ' . BASE_URL . '/views/admin/productos.php?status=visibilidad');
        exit;
    } else {
        header('Location: ' . BASE_URL . '/views/admin/productos.php?status=error');
        exit;
    }
} catch (Exception $e) {
    error_log("Error en toggle_visibilidad: " . $e->getMessage());
    header('Location: ' . BASE_URL . '/views/admin/productos.php?status=error');
    exit;
}
?>
