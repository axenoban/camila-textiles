<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/pedido.php';

// Verificar que el usuario tiene el rol de administrador
session_start();
$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario || ($usuario['rol'] ?? null) !== 'administrador') {
    header('Location: ' . BASE_URL . '/views/public/login.php');
    exit;
}

$pedidoModel = new Pedido();
$accion = $_POST['accion'] ?? $_GET['accion'] ?? null;

function redirigirPedidos(array $params = []): void
{
    $url = BASE_URL . '/views/admin/pedidos.php';
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    header('Location: ' . $url);
    exit;
}

try {
    switch ($accion) {
        case 'cancelar':
            $idPedido = filter_input(INPUT_POST, 'id_pedido', FILTER_VALIDATE_INT);
            $motivoCancelacion = filter_input(INPUT_POST, 'motivo_cancelacion', FILTER_SANITIZE_STRING);

            if ($idPedido) {
                // Obtener los pedidos agrupados por cliente, producto y fecha
                $pedidosAgrupados = $pedidoModel->obtenerPedidosAgrupadosPorPedido($idPedido);

                // Cancelar todos los pedidos en el grupo
                foreach ($pedidosAgrupados as $pedido) {
                    // Actualizamos el estado de cada pedido individual en el grupo
                    $pedidoModel->actualizarEstado($pedido['id_pedido'], 'cancelado', $motivoCancelacion);
                }

                // También puedes actualizar el estado de la agrupación (por si es necesario)
                $pedidoModel->actualizarEstadoAgrupado($idPedido, 'cancelado');

                echo json_encode(['status' => 'success', 'message' => 'Pedidos cancelados exitosamente.']);
            }
            break;

        default:
            redirigirPedidos();
            break;
    }
} catch (Throwable $e) {
    error_log('Error en la gestión de pedidos: ' . $e->getMessage());
    redirigirPedidos(['status' => 'error']);
}
