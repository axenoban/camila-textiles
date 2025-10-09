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

        // Verificamos si el usuario existe y la clave es correcta
        if ($usuario && password_verify($clave, $usuario['clave'])) {
            return $usuario;
        }
        return false;
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
