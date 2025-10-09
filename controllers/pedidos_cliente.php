<?php
session_start();

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../database/conexion.php';
require_once __DIR__ . '/../models/pedido.php';
require_once __DIR__ . '/../models/producto.php';
require_once __DIR__ . '/../models/inventario.php';

if (!isset($_SESSION['usuario']) || ($_SESSION['rol'] ?? '') !== 'cliente') {
    header('Location: ' . BASE_URL . '/views/public/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/views/cliente/productos.php');
    exit;
}

$idUsuario = (int) ($_SESSION['usuario']['id'] ?? 0);
$idProducto = filter_input(INPUT_POST, 'producto_id', FILTER_VALIDATE_INT);
$colorId = filter_input(INPUT_POST, 'color_id', FILTER_VALIDATE_INT);
$presentacionId = filter_input(INPUT_POST, 'presentacion_id', FILTER_VALIDATE_INT);
$cantidadSolicitada = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_FLOAT);

$_SESSION['reserva_tipo'] = 'danger';
$_SESSION['reserva_mensaje'] = 'No fue posible registrar tu reserva. Revisa los datos ingresados.';

if (!$idProducto || !$colorId || !$presentacionId || !$cantidadSolicitada || $cantidadSolicitada <= 0) {
    header('Location: ' . BASE_URL . '/views/cliente/detalle_producto.php?id=' . $idProducto);
    exit;
}

$productoModel = new Producto();
$producto = $productoModel->obtenerProductoPorId($idProducto);

if (!$producto || !(bool) ($producto['visible'] ?? true)) {
    $_SESSION['reserva_mensaje'] = 'El producto seleccionado no está disponible.';
    header('Location: ' . BASE_URL . '/views/cliente/productos.php');
    exit;
}

$color = $productoModel->obtenerColorPorId($colorId);
$presentacion = $productoModel->obtenerPresentacionPorId($presentacionId);

if (!$color || !$presentacion || (int) $color['id_producto'] !== (int) $producto['id'] || (int) $presentacion['id_producto'] !== (int) $producto['id']) {
    $_SESSION['reserva_mensaje'] = 'La variación seleccionada no existe para este producto.';
    header('Location: ' . BASE_URL . '/views/cliente/detalle_producto.php?id=' . $idProducto);
    exit;
}

$esRollo = ($presentacion['tipo'] ?? '') === 'rollo';

if ($esRollo && floor($cantidadSolicitada) != $cantidadSolicitada) {
    $_SESSION['reserva_mensaje'] = 'Para compras por rollo debes seleccionar cantidades enteras.';
    header('Location: ' . BASE_URL . '/views/cliente/detalle_producto.php?id=' . $idProducto);
    exit;
}

if (!$esRollo && floor($cantidadSolicitada) != $cantidadSolicitada) {
    $_SESSION['reserva_mensaje'] = 'Para pedidos por metro trabaja con cantidades enteras para agilizar el corte en almacén.';
    header('Location: ' . BASE_URL . '/views/cliente/detalle_producto.php?id=' . $idProducto);
    exit;
}

$stockVariante = $productoModel->obtenerStockVariante($idProducto, $colorId, $presentacionId);

if ($stockVariante <= 0) {
    $_SESSION['reserva_mensaje'] = 'La combinación de color y presentación está agotada.';
    header('Location: ' . BASE_URL . '/views/cliente/detalle_producto.php?id=' . $idProducto);
    exit;
}

if ($cantidadSolicitada > $stockVariante) {
    $_SESSION['reserva_mensaje'] = 'Solo hay ' . rtrim(rtrim(number_format($stockVariante, 2, '.', ''), '0'), '.') . ' unidades disponibles para esta combinación.';
    header('Location: ' . BASE_URL . '/views/cliente/detalle_producto.php?id=' . $idProducto);
    exit;
}

$cantidadSolicitada = (int) $cantidadSolicitada;

$precioUnitario = (float) ($presentacion['precio'] ?? 0);
$unidad = $presentacion['tipo'] ?? 'metro';
$metrosPorUnidad = (float) ($presentacion['metros_por_unidad'] ?? 1);
$equivalencia = $esRollo ? max(1, (int) round($metrosPorUnidad)) : 1;
$cantidadEstandarizada = (int) $cantidadSolicitada * $equivalencia;
$total = $precioUnitario * $cantidadSolicitada;

$inventarioModel = new Inventario();
$stockDisponible = $inventarioModel->obtenerStockPorProducto($idProducto);

if ($stockDisponible <= 0) {
    $_SESSION['reserva_mensaje'] = 'El producto no tiene stock disponible por el momento.';
    header('Location: ' . BASE_URL . '/views/cliente/detalle_producto.php?id=' . $idProducto);
    exit;
}

if ($cantidadEstandarizada > $stockDisponible) {
    $_SESSION['reserva_mensaje'] = 'Actualmente solo podemos comprometer ' . $stockDisponible . ' metros equivalentes para este producto.';
    header('Location: ' . BASE_URL . '/views/cliente/detalle_producto.php?id=' . $idProducto);
    exit;
}

$pedidoModel = new Pedido();

try {
    global $pdo;
    $pdo->beginTransaction();

    $pedidoRegistrado = $pedidoModel->crearPedido(
        $idUsuario,
        $idProducto,
        $colorId,
        $presentacionId,
        $cantidadSolicitada,
        $unidad,
        $precioUnitario,
        $total
    );

    $varianteActualizada = $productoModel->disminuirStockVariante($idProducto, $colorId, $presentacionId, $cantidadSolicitada);
    $inventarioActualizado = $inventarioModel->disminuirStock($idProducto, $cantidadEstandarizada);

    if ($pedidoRegistrado && $varianteActualizada && $inventarioActualizado) {
        $pdo->commit();
        $_SESSION['reserva_tipo'] = 'success';
        $_SESSION['reserva_mensaje'] = 'Reserva registrada correctamente. Puedes seguir su estado en la sección "Mis pedidos".';
    } else {
        $pdo->rollBack();
        $_SESSION['reserva_mensaje'] = 'No fue posible registrar tu reserva. Inténtalo de nuevo en unos minutos.';
    }
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Error al registrar reserva: ' . $e->getMessage());
    $_SESSION['reserva_mensaje'] = 'Hubo un inconveniente al guardar tu reserva. Por favor intenta más tarde.';
}

header('Location: ' . BASE_URL . '/views/cliente/pedidos.php');
exit;
