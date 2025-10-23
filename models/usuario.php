<?php
require_once __DIR__ . '/../database/conexion.php';

class Usuario
{
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
                return 'Cuenta bloqueada, contáctese con la administración.'; // Mensaje de cuenta bloqueada
            }

            // Si la contraseña está hasheada correctamente (BCRYPT, ARGON, etc.)
            if (password_verify($clave, $usuario['clave'])) {
                return $usuario; // Devuelve el array de usuario
            }

            // ⚠️ En caso de contraseñas antiguas sin hash (texto plano)
            if (hash_equals($usuario['clave'], $clave)) {
                // Actualizamos inmediatamente a formato seguro
                $nuevoHash = password_hash($clave, PASSWORD_DEFAULT);
                $update = $pdo->prepare("UPDATE usuarios SET clave = :clave WHERE id = :id");
                $update->execute(['clave' => $nuevoHash, 'id' => $usuario['id']]);
                $usuario['clave'] = $nuevoHash;
                return $usuario; // Devuelve el array de usuario
            }

            return false; // Si las contraseñas no coinciden
        } catch (PDOException $e) {
            error_log("Error al autenticar usuario: " . $e->getMessage());
            return false; // Error de autenticación
        }
    }



    // ✅ Contar clientes activos
    public function contarClientesActivos()
    {
        global $pdo;
        return (int)$pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'cliente'")->fetchColumn();
    }

    // ✅ Obtener todos los usuarios
    public function obtenerUsuarios()
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM usuarios");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Obtener usuario por ID
    public function obtenerUsuarioPorId($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ Actualizar perfil
    public function actualizarPerfil($id, $nombre, $email, $clave = null)
    {
        global $pdo;

        $campos = ['nombre' => $nombre, 'email' => $email, 'id' => $id];
        $sql = "UPDATE usuarios SET nombre = :nombre, email = :email";

        if (!empty($clave)) {
            $campos['clave'] = password_hash($clave, PASSWORD_DEFAULT);
            $sql .= ", clave = :clave";
        }

        $sql .= " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($campos);
    }

    // ✅ Crear nuevo usuario
    public function crearUsuario($nombre, $email, $clave, $rol = 'cliente')
    {
        global $pdo;

        $claveHash = password_hash($clave, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            INSERT INTO usuarios (nombre, email, clave, rol) 
            VALUES (:nombre, :email, :clave, :rol)
        ");
        return $stmt->execute([
            'nombre' => $nombre,
            'email' => $email,
            'clave' => $claveHash,
            'rol' => $rol
        ]);
    }
}
