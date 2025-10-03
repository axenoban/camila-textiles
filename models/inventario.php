<?php
// inventario.php

require_once __DIR__ . '/../database/conexion.php';

class Inventario {

    // Método para obtener todos los productos del inventario
    public function obtenerInventario() {
        global $pdo;

        $stmt = $pdo->prepare("SELECT i.id_producto, p.nombre, i.cantidad FROM inventarios i INNER JOIN productos p ON i.id_producto = p.id ORDER BY p.nombre ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para sumar el stock total disponible
    public function obtenerStockTotal() {
        global $pdo;

        return (int) $pdo->query('SELECT COALESCE(SUM(cantidad), 0) FROM inventarios')->fetchColumn();
    }

    // Método para encontrar productos con bajo stock
    public function obtenerAlertasDeStock($umbral = 20) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT p.nombre, i.cantidad FROM inventarios i INNER JOIN productos p ON i.id_producto = p.id WHERE i.cantidad <= :umbral ORDER BY i.cantidad ASC");
        $stmt->bindValue(':umbral', (int) $umbral, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para agregar un producto al inventario
    public function agregarAlInventario($idProducto, $cantidad) {
        global $pdo;

        $stmt = $pdo->prepare("INSERT INTO inventarios (id_producto, cantidad) VALUES (:id_producto, :cantidad)");
        return $stmt->execute(['id_producto' => $idProducto, 'cantidad' => $cantidad]);
    }

    // Método para eliminar un producto del inventario
    public function eliminarDelInventario($idProducto) {
        global $pdo;

        $stmt = $pdo->prepare("DELETE FROM inventarios WHERE id_producto = :id_producto");
        return $stmt->execute(['id_producto' => $idProducto]);
    }
}
?>
