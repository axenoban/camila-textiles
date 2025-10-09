<?php
// views/cliente/includes/init.php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../../../config/app.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: ' . BASE_URL . '/views/public/login.php');
    exit;
}

$clienteActual = $_SESSION['usuario'];

if (($clienteActual['rol'] ?? '') !== 'cliente') {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}
