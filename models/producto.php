<?php
// producto.php

require_once 'conexion.php';

class Producto {

    // Método para obtener todos los productos visibles
    public function obtenerProductosVisibles() {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM productos WHERE visible = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener un producto por ID
    public function obtenerProductoPorId($id) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para agregar un nuevo producto
    public function agregarProducto($nombre, $descripcion, $color, $unidadVenta, $precio, $imagen) {
        global $pdo;

        $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, color, unidad_venta, precio, imagen) VALUES (:nombre, :descripcion, :color, :unidad_venta, :precio, :imagen)");
        return $stmt->execute([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'color' => $color,
            'unidad_venta' => $unidadVenta,
            'precio' => $precio,
            'imagen' => $imagen
        ]);
    }

    // Método para editar un producto
    public function editarProducto($id, $nombre, $descripcion, $color, $unidadVenta, $precio, $imagen) {
        global $pdo;

        $stmt = $pdo->prepare("UPDATE productos SET nombre = :nombre, descripcion = :descripcion, color = :color, unidad_venta = :unidad_venta, precio = :precio, imagen = :imagen WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'color' => $color,
            'unidad_venta' => $unidadVenta,
            'precio' => $precio,
            'imagen' => $imagen
        ]);
    }

    // Método para eliminar un producto
    public function eliminarProducto($id) {
        global $pdo;

        $stmt = $pdo->prepare("DELETE FROM productos WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>
