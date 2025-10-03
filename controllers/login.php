<?php
// login.php

session_start();
require_once 'models/usuario.php';

class LoginController {

    public function mostrarLogin() {
        include('views/public/login.php');
    }

    public function autenticar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = $_POST['usuario'];
            $clave = $_POST['clave'];
            $usuarioModel = new Usuario();
            $resultado = $usuarioModel->autenticarUsuario($usuario, $clave);

            if ($resultado) {
                $_SESSION['usuario'] = $usuario;
                header('Location: /panel');  // Redirige al panel del cliente o admin
            } else {
                echo "Credenciales incorrectas";
            }
        }
    }

    public function cerrarSesion() {
        session_unset();
        session_destroy();
        header('Location: /');
    }
}
?>
