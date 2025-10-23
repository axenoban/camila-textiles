<?php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/cliente.php'; // Usamos la clase Cliente

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificamos si el usuario tiene el rol adecuado
if (empty($_SESSION['usuario']) || ($_SESSION['usuario']['rol'] ?? '') !== 'administrador') {
    header('Location: ' . BASE_URL . '/views/public/login.php');
    exit;
}

$clienteModel = new Cliente(); // Instanciamos el modelo de Cliente

$accion = $_GET['accion'] ?? null;

function redirigirClientes(array $params = []): void {
    $url = BASE_URL . '/views/admin/clientes.php'; 
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    header('Location: ' . $url);
    exit;
}

try {
    switch ($accion) {
        case 'cambiarEstado':
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            $estado = $_GET['estado'] ?? 'habilitado';

            if ($id) {
                // Cambiar el estado del cliente
                $clienteModel->cambiarEstadoCliente($id, $estado);
                redirigirClientes(['status' => $estado === 'habilitado' ? 'habilitado' : 'bloqueado']);
            }
            redirigirClientes(['status' => 'error']);
            break;

        case 'eliminar':
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($id) {
                // Eliminar el cliente
                $clienteModel->eliminarCliente($id);
                redirigirClientes(['status' => 'eliminado']);
            }
            redirigirClientes(['status' => 'error']);
            break;

        default:
            redirigirClientes();
    }
} catch (Throwable $e) {
    error_log('Error al procesar la solicitud: ' . $e->getMessage());
    redirigirClientes(['status' => 'error']);
}

// Obtener los clientes
$clientes = $clienteModel->obtenerClientes();

// Obtener pedidos y ventas de cada cliente
foreach ($clientes as &$cliente) {
    $pedidosYVentas = $clienteModel->obtenerPedidosYVentas($cliente['id']);
    $cliente['total_pedidos'] = $pedidosYVentas['total_pedidos'];
    $cliente['total_ventas'] = $pedidosYVentas['total_ventas'];
}

?>
