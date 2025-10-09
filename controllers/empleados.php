<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/empleado.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['usuario']) || ($_SESSION['rol'] ?? null) !== 'administrador') {
    header('Location: ' . BASE_URL . '/views/public/login.php');
    exit;
}

$empleadoModel = new Empleado();
$accion = $_POST['accion'] ?? $_GET['accion'] ?? null;

function redirigirEmpleados(array $params = []): void {
    $url = BASE_URL . '/views/admin/empleados.php';
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
            $puesto = trim($_POST['puesto'] ?? '');
            $salario = $_POST['salario'] ?? null;

            if ($nombre === '' || $puesto === '' || $salario === null) {
                redirigirEmpleados(['status' => 'error']);
            }

            $empleadoModel->agregarEmpleado($nombre, $puesto, (float) $salario);
            redirigirEmpleados(['status' => 'creado']);
            break;

        case 'actualizar':
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $nombre = trim($_POST['nombre'] ?? '');
            $puesto = trim($_POST['puesto'] ?? '');
            $salario = $_POST['salario'] ?? null;

            if (!$id || $nombre === '' || $puesto === '' || $salario === null) {
                redirigirEmpleados(['status' => 'error']);
            }

            $empleadoModel->editarEmpleado($id, $nombre, $puesto, (float) $salario);
            redirigirEmpleados(['status' => 'actualizado']);
            break;

        case 'eliminar':
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($id) {
                $empleadoModel->eliminarEmpleado($id);
                redirigirEmpleados(['status' => 'eliminado']);
            }
            redirigirEmpleados(['status' => 'error']);
            break;

        default:
            redirigirEmpleados();
    }
} catch (Throwable $e) {
    error_log('Error en gestión de empleados: ' . $e->getMessage());
    redirigirEmpleados(['status' => 'error']);
}
