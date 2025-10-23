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

function redirigirSucursales(array $params = []): void
{
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
            // Recoger datos del formulario
            $nombre = trim($_POST['nombre'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $horario = trim($_POST['horario_apertura'] ?? '');
            $latitud = trim($_POST['latitud'] ?? '');
            $longitud = trim($_POST['longitud'] ?? '');
            $visible = isset($_POST['visible']) ? 1 : 0;

            // Validar que todos los campos necesarios estén llenos
            if ($nombre === '' || $direccion === '' || $telefono === '' || $horario === '' || $latitud === '' || $longitud === '') {
                redirigirSucursales(['status' => 'error']);
            }

            // Agregar la sucursal con las coordenadas
            $sucursalModel->agregarSucursal($nombre, $direccion, $telefono, $horario, $latitud, $longitud, $visible);
            redirigirSucursales(['status' => 'creado']);
            break;

        case 'actualizar':
            // Recoger datos del formulario
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $nombre = trim($_POST['nombre'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $horario = trim($_POST['horario_apertura'] ?? '');
            $latitud = trim($_POST['latitud'] ?? '');
            $longitud = trim($_POST['longitud'] ?? '');
            $visible = isset($_POST['visible']) ? 1 : 0;

            // Validar que los datos sean correctos
            if (!$id || $nombre === '' || $direccion === '' || $telefono === '' || $horario === '' || $latitud === '' || $longitud === '') {
                redirigirSucursales(['status' => 'error']);
            }

            // Actualizar la sucursal con las nuevas coordenadas
            $sucursalModel->editarSucursal($id, $nombre, $direccion, $telefono, $horario, $latitud, $longitud, $visible);
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

        case 'toggle_visibilidad':
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($id) {
                $sucursalModel->toggleVisibilidad($id);
                redirigirSucursales(['status' => 'visibilidad']);
            }
            redirigirSucursales(['status' => 'error']);

        default:
            redirigirSucursales();
    }
} catch (Throwable $e) {
    error_log('Error en gestión de sucursales: ' . $e->getMessage());
    redirigirSucursales(['status' => 'error']);
}
