<?php
session_start();

require_once __DIR__ . '/../config/app.php';
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
$cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT);

$_SESSION['reserva_tipo'] = 'danger';
$_SESSION['reserva_mensaje'] = 'No fue posible registrar tu reserva. Revisa los datos ingresados.';

if (!$idProducto || !$cantidad || $cantidad <= 0) {
    header('Location: ' . BASE_URL . '/views/cliente/productos.php');
    exit;
}

$productoModel = new Producto();
$producto = $productoModel->obtenerProductoPorId($idProducto);

if (!$producto || !(bool) ($producto['visible'] ?? true)) {
    $_SESSION['reserva_mensaje'] = 'El producto seleccionado no está disponible.';
    header('Location: ' . BASE_URL . '/views/cliente/productos.php');
    exit;
}

$inventarioModel = new Inventario();
$stockDisponible = $inventarioModel->obtenerStockPorProducto($idProducto);

if ($stockDisponible <= 0) {
    $_SESSION['reserva_mensaje'] = 'El producto no tiene stock disponible por el momento.';
    header('Location: ' . BASE_URL . '/views/cliente/productos.php');
    exit;
}

if ($cantidad > $stockDisponible) {
    $_SESSION['reserva_mensaje'] = 'Solo hay ' . $stockDisponible . ' unidades disponibles para reservar.';
    header('Location: ' . BASE_URL . '/views/cliente/productos.php');
    exit;
}

$pedidoModel = new Pedido();

if ($pedidoModel->crearPedido($idUsuario, $idProducto, $cantidad) && $inventarioModel->disminuirStock($idProducto, $cantidad)) {
    $_SESSION['reserva_tipo'] = 'success';
    $_SESSION['reserva_mensaje'] = 'Reserva registrada correctamente. Puedes seguir su estado en la sección "Mis pedidos".';
} else {
    $_SESSION['reserva_mensaje'] = 'No fue posible registrar tu reserva. Inténtalo de nuevo en unos minutos.';
}

header('Location: ' . BASE_URL . '/views/cliente/pedidos.php');
exit;
