<?php
session_start();

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/usuario.php';

if (!isset($_SESSION['usuario']) || ($_SESSION['rol'] ?? '') !== 'cliente') {
    header('Location: ' . BASE_URL . '/views/public/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/views/cliente/perfil.php');
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$clave = trim($_POST['clave'] ?? '');

$errores = [];

if ($nombre === '') {
    $errores[] = 'Ingresa tu nombre completo.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'Ingresa un correo electrónico válido.';
}

if ($clave !== '' && strlen($clave) < 8) {
    $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
}

$usuarioModel = new Usuario();
$idUsuario = (int) $_SESSION['usuario']['id'];

if (empty($errores)) {
    $claveActualizar = $clave !== '' ? $clave : null;

    if ($usuarioModel->actualizarPerfil($idUsuario, $nombre, $email, $claveActualizar)) {
        $_SESSION['usuario']['nombre'] = $nombre;
        $_SESSION['usuario']['email'] = $email;

        if ($claveActualizar !== null) {
            session_regenerate_id(true);
        }

        $_SESSION['perfil_tipo'] = 'success';
        $_SESSION['perfil_mensaje'] = 'Tu perfil se actualizó correctamente.';
        header('Location: ' . BASE_URL . '/views/cliente/perfil.php');
        exit;
    }

    $errores[] = 'Ocurrió un problema al guardar los cambios. Inténtalo nuevamente.';
}

$_SESSION['perfil_tipo'] = 'danger';
$_SESSION['perfil_mensaje'] = implode(' ', $errores);
header('Location: ' . BASE_URL . '/views/cliente/perfil.php');
exit;
