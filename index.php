<?php
session_start();
require_once __DIR__ . '/config/app.php';

// Verificamos si el usuario está autenticado
if (isset($_SESSION['usuario'])) {
    // Si el usuario es un administrador, lo redirigimos al panel de administración
    if ($_SESSION['rol'] == 'administrador') {
        header('Location: ' . BASE_URL . '/views/admin/dashboard.php');
        exit();
    }
    // Si el usuario es un cliente, lo redirigimos al panel del cliente
    elseif ($_SESSION['rol'] == 'cliente') {
        header('Location: ' . BASE_URL . '/views/cliente/dashboard.php');
        exit();
    }
} else {
    // Si no está autenticado, lo redirigimos a la página pública (inicio)
    header('Location: ' . BASE_URL . '/views/public/index.php');
    exit();
}
?>
