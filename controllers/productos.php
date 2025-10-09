<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/producto.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['usuario']) || ($_SESSION['rol'] ?? null) !== 'administrador') {
    header('Location: ' . BASE_URL . '/views/public/login.php');
    exit;
}

$productoModel = new Producto();
$accion = $_POST['accion'] ?? $_GET['accion'] ?? null;

function redirigirProductos(array $params = []): void {
    $url = BASE_URL . '/views/admin/productos.php';
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    header('Location: ' . $url);
    exit;
}

try {
    switch ($accion) {
        case 'crear':
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $precio = $_POST['precio'] ?? null;
            $imagen = trim($_POST['imagen'] ?? '');

            if ($nombre === '' || $descripcion === '' || $precio === null || $imagen === '') {
                redirigirProductos(['status' => 'error']);
            }

            $productoModel->agregarProducto($nombre, $descripcion, (float) $precio, $imagen);
            redirigirProductos(['status' => 'creado']);
            break;

        case 'actualizar':
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $precio = $_POST['precio'] ?? null;
            $imagen = trim($_POST['imagen'] ?? '');

            if (!$id || $nombre === '' || $descripcion === '' || $precio === null || $imagen === '') {
                redirigirProductos(['status' => 'error']);
            }

            $productoModel->editarProducto($id, $nombre, $descripcion, (float) $precio, $imagen);
            redirigirProductos(['status' => 'actualizado']);
            break;

        case 'eliminar':
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($id) {
                $productoModel->eliminarProducto($id);
                redirigirProductos(['status' => 'eliminado']);
            }
            redirigirProductos(['status' => 'error']);
            break;

        case 'cambiar_visibilidad':
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $visible = filter_input(INPUT_POST, 'visible', FILTER_VALIDATE_INT);

            if ($id === null || $visible === null) {
                redirigirProductos(['status' => 'error']);
            }

            $actualizado = $productoModel->actualizarVisibilidad($id, (int) $visible === 1);
            $estado = (int) $visible === 1 ? 'visibilidad_on' : 'visibilidad_off';

            redirigirProductos(['status' => $actualizado ? $estado : 'error']);
            break;

        default:
            redirigirProductos();
    }
} catch (Throwable $e) {
    error_log('Error en gestión de productos: ' . $e->getMessage());
    redirigirProductos(['status' => 'error']);
}
