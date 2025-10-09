<?php
// controllers/auth/logout.php

session_start();

// Limpiar y destruir la sesión activa
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();

// Redirigir al catálogo público
header('Location: /camila-textil/views/public/index.php');
exit;
