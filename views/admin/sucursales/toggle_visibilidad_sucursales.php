<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/sucursal.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario es un administrador
if (empty($_SESSION['usuario']) || ($_SESSION['rol'] ?? null) !== 'administrador') {
    header('Location: ' . BASE_URL . '/views/public/login.php');
    exit;
}

$sucursalModel = new Sucursal();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    // Cambiar visibilidad de la sucursal
    $sucursalModel->toggleVisibilidad($id);
    header('Location: ' . BASE_URL . '/views/admin/sucursales.php?status=visibilidad');
    exit;
}

header('Location: ' . BASE_URL . '/views/admin/sucursales.php?status=error');
exit;
