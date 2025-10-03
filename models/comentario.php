<?php
// comentario.php

require_once 'conexion.php';

class Comentario {

    // Método para obtener todos los comentarios
    public function obtenerComentarios() {
        global $pdo;

        $stmt = $pdo->prepare("SELECT c.*, p.nombre AS producto, u.nombre AS usuario FROM comentarios c INNER JOIN productos p ON c.id_producto = p.id INNER JOIN usuarios u ON c.id_usuario = u.id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para agregar un comentario
    public function agregarComentario($idProducto, $idUsuario, $comentario) {
        global $pdo;

        $stmt = $pdo->prepare("INSERT INTO comentarios (id_producto, id_usuario, comentario) VALUES (:id_producto, :id_usuario, :comentario)");
        return $stmt->execute(['id_producto' => $idProducto, 'id_usuario' => $idUsuario, 'comentario' => $comentario]);
    }

    // Método para eliminar un comentario
    public function eliminarComentario($id) {
        global $pdo;

        $stmt = $pdo->prepare("DELETE FROM comentarios WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>
