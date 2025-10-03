<?php
session_start();

// Verificamos si el usuario está autenticado
if (isset($_SESSION['usuario'])) {
    // Si el usuario es un administrador, lo redirigimos al panel de administración
    if ($_SESSION['rol'] == 'administrador') {
        header('Location: /camila-textil/views/admin/dashboard.php');
        exit();
    } 
    // Si el usuario es un cliente, lo redirigimos al panel del cliente
    elseif ($_SESSION['rol'] == 'cliente') {
        header('Location: /camila-textil/views/cliente/dashboard.php');
        exit();
    }
} else {
    // Si no está autenticado, lo redirigimos a la página pública (inicio)
    header('Location: /camila-textil/views/public/index.php');
    exit();
}
?>
