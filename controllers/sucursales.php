<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/sucursal.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['usuario']) || ($_SESSION['rol'] ?? null) !== 'administrador') {
    header('Location: ' . BASE_URL . '/views/public/login.php');
    exit;
}

$sucursalModel = new Sucursal();
$accion = $_POST['accion'] ?? $_GET['accion'] ?? null;

function redirigirSucursales(array $params = []): void {
    $url = BASE_URL . '/views/admin/sucursales.php';
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
            $direccion = trim($_POST['direccion'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $horario = trim($_POST['horario_apertura'] ?? '');

            if ($nombre === '' || $direccion === '' || $telefono === '' || $horario === '') {
                redirigirSucursales(['status' => 'error']);
            }

            $sucursalModel->agregarSucursal($nombre, $direccion, $telefono, $horario);
            redirigirSucursales(['status' => 'creado']);
            break;

        case 'actualizar':
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $nombre = trim($_POST['nombre'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $horario = trim($_POST['horario_apertura'] ?? '');

            if (!$id || $nombre === '' || $direccion === '' || $telefono === '' || $horario === '') {
                redirigirSucursales(['status' => 'error']);
            }

            $sucursalModel->editarSucursal($id, $nombre, $direccion, $telefono, $horario);
            redirigirSucursales(['status' => 'actualizado']);
            break;

        case 'eliminar':
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($id) {
                $sucursalModel->eliminarSucursal($id);
                redirigirSucursales(['status' => 'eliminado']);
            }
            redirigirSucursales(['status' => 'error']);
            break;

        default:
            redirigirSucursales();
    }
} catch (Throwable $e) {
    error_log('Error en gestión de sucursales: ' . $e->getMessage());
    redirigirSucursales(['status' => 'error']);
}
