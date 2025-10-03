<?php
// usuario.php

require_once __DIR__ . '/../database/conexion.php';

class Usuario {

    // Método para autenticar un usuario
    public function autenticarUsuario($email, $clave) {
        global $pdo;
        
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $infoClave = password_get_info($usuario['clave']);

            // Si la clave no está cifrada, validamos en texto plano y la ciframos para futuros accesos
            if ($infoClave['algo'] === 0) {
                if (hash_equals($usuario['clave'], $clave)) {
                    $nuevoHash = password_hash($clave, PASSWORD_BCRYPT);
                    $actualizar = $pdo->prepare('UPDATE usuarios SET clave = :clave WHERE id = :id');
                    $actualizar->execute(['clave' => $nuevoHash, 'id' => $usuario['id']]);
                    $usuario['clave'] = $nuevoHash;
                } else {
                    return false;
                }
            }

            if (password_verify($clave, $usuario['clave'])) {
                return $usuario;
            }
        }
        return false;
    }

    public function contarClientesActivos() {
        global $pdo;

        return (int) $pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'cliente'")->fetchColumn();
    }

    // Método para obtener todos los usuarios
    public function obtenerUsuarios() {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM usuarios");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener un usuario por ID
    public function obtenerUsuarioPorId($id) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para crear un nuevo usuario
    public function crearUsuario($nombre, $email, $clave, $rol = 'cliente') {
        global $pdo;
        
        $claveEncriptada = password_hash($clave, PASSWORD_BCRYPT);  // Encriptar la clave
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, clave, rol) VALUES (:nombre, :email, :clave, :rol)");
        return $stmt->execute(['nombre' => $nombre, 'email' => $email, 'clave' => $claveEncriptada, 'rol' => $rol]);
    }
}
?>
