<?php
// login.php

session_start();
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/usuario.php';

class LoginController {

    public function mostrarLogin() {
        include('views/public/login.php');
    }

    public function autenticar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = trim($_POST['usuario'] ?? $_POST['email'] ?? '');
            $clave = $_POST['clave'] ?? '';
            $usuarioModel = new Usuario();
            $resultado = $usuarioModel->autenticarUsuario($usuario, $clave);

            if ($resultado) {
                session_regenerate_id(true);
                $_SESSION['usuario'] = [
                    'id' => $resultado['id'],
                    'nombre' => $resultado['nombre'],
                    'email' => $resultado['email'],
                    'rol' => $resultado['rol'],
                ];
                $_SESSION['rol'] = $resultado['rol'];
                $destino = $resultado['rol'] === 'administrador'
                    ? BASE_URL . '/views/admin/dashboard.php'
                    : BASE_URL . '/views/cliente/dashboard.php';
                header('Location: ' . $destino);
                exit;
            } else {
                $_SESSION['error_login'] = 'Las credenciales no coinciden con nuestros registros.';
                header('Location: ' . BASE_URL . '/views/public/login.php');
                exit;
            }
        }
    }

    public function cerrarSesion() {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/views/public/login.php');
    }
}
?>
