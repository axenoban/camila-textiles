<?php
// pedido.php

require_once __DIR__ . '/../database/conexion.php';

class Pedido {

    // Método para obtener los pedidos de un cliente
    public function obtenerPedidosPorCliente($idUsuario) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id_usuario = :id_usuario");
        $stmt->execute(['id_usuario' => $idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener todos los pedidos
    public function obtenerTodosLosPedidos() {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM pedidos");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para confirmar un pedido
    public function confirmarPedido($id) {
        global $pdo;

        $stmt = $pdo->prepare("UPDATE pedidos SET estado = 'confirmado' WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Método para completar un pedido
    public function completarPedido($id) {
        global $pdo;

        $stmt = $pdo->prepare("UPDATE pedidos SET estado = 'completado' WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Método para cancelar un pedido
    public function cancelarPedido($id) {
        global $pdo;

        $stmt = $pdo->prepare("UPDATE pedidos SET estado = 'cancelado' WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>
