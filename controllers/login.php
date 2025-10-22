<?php
// login.php

session_start();
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/usuario.php';

class LoginController
{

    public function mostrarLogin()
    {
        include('views/public/login.php');
    }

    public function autenticar()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $usuario = trim($_POST['usuario'] ?? $_POST['email'] ?? '');
        $clave = $_POST['clave'] ?? '';
        $usuarioModel = new Usuario();
        $resultado = $usuarioModel->autenticarUsuario($usuario, $clave);

        // Verificamos si el resultado es un array (es decir, autenticación exitosa)
        if (is_array($resultado)) {
            // Si el estado del usuario no es 'habilitado', redirigir con un mensaje
            if ($resultado['estado'] !== 'habilitado') {
                $_SESSION['error_login'] = 'Cuenta bloqueada, contáctese con la administración.';
                header('Location: ' . BASE_URL . '/views/public/login.php');
                exit;
            }

            // Si es válido, regenera la sesión y redirige al dashboard adecuado
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
        } elseif ($resultado === false) {
            // Si no se encuentra el usuario o las credenciales son incorrectas
            $_SESSION['error_login'] = 'Las credenciales no coinciden con nuestros registros.';
            header('Location: ' . BASE_URL . '/views/public/login.php');
            exit;
        } else {
            // Si es un mensaje de error específico como 'Cuenta bloqueada'
            $_SESSION['error_login'] = $resultado;
            header('Location: ' . BASE_URL . '/views/public/login.php');
            exit;
        }
    }
}
public function autenticarUsuario($identificador, $clave)
{
    global $pdo;

    try {
        // Buscar por email o nombre
        $stmt = $pdo->prepare("
            SELECT * FROM usuarios 
            WHERE email = :identificador OR nombre = :identificador 
            LIMIT 1
        ");
        $stmt->execute(['identificador' => $identificador]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si no se encuentra el usuario
        if (!$usuario) {
            return false;
        }

        // Verificar si la cuenta está bloqueada
        if ($usuario['estado'] !== 'habilitado') {
            return 'Cuenta bloqueada, contáctese con la administración.'; // Mensaje para el estado bloqueado
        }

        // Si la contraseña está hasheada correctamente (BCRYPT, ARGON, etc.)
        if (password_verify($clave, $usuario['clave'])) {
            return $usuario; // Devuelve el array de usuario si la contraseña es correcta
        }

        // ⚠️ En caso de contraseñas antiguas sin hash (texto plano)
        if (hash_equals($usuario['clave'], $clave)) {
            // Actualizamos inmediatamente a formato seguro
            $nuevoHash = password_hash($clave, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE usuarios SET clave = :clave WHERE id = :id");
            $update->execute(['clave' => $nuevoHash, 'id' => $usuario['id']]);
            $usuario['clave'] = $nuevoHash;
            return $usuario; // Devuelve el array de usuario si la contraseña es correcta
        }

        return false; // Si las contraseñas no coinciden
    } catch (PDOException $e) {
        error_log("Error al autenticar usuario: " . $e->getMessage());
        return false; // Error de autenticación
    }
}




    public function cerrarSesion()
    {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/views/public/login.php');
    }
}
?>
