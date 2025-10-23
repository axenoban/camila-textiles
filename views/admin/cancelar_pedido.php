<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../models/pedido.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: ' . BASE_URL . '/views/admin/pedidos.php');
    exit;
}

$pedidoModel = new Pedido();
$accion = basename(__FILE__, '.php'); // confirmar_pedido, cancelar_pedido, etc.

$estado = match ($accion) {
    'confirmar_pedido' => 'confirmado',
    'cancelar_pedido' => 'cancelado',
    'completar_pedido' => 'completado',
    default => 'pendiente'
};

$pedidoModel->actualizarEstadoAgrupado($id, $estado);

header('Location: ' . BASE_URL . '/views/admin/pedidos.php');
exit;
