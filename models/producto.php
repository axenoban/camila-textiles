<?php
// producto.php

require_once __DIR__ . '/../database/conexion.php';

class Producto {

    // Método para obtener todos los productos visibles
    public function obtenerProductosVisibles() {
        global $pdo;

        $stmt = $pdo->prepare("SELECT p.*, COALESCE(i.cantidad, 0) AS stock FROM productos p LEFT JOIN inventarios i ON i.id_producto = p.id WHERE p.visible = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener todos los productos sin filtro de visibilidad
    public function obtenerTodosLosProductos() {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM productos ORDER BY fecha_creacion DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener productos recientes para destacar en portada
    public function obtenerProductosDestacados($limite = 6) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM productos WHERE visible = 1 ORDER BY fecha_creacion DESC LIMIT :limite");
        $stmt->bindValue(':limite', (int) $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para contar los productos visibles en el catálogo
    public function contarProductosVisibles() {
        global $pdo;

        return (int) $pdo->query("SELECT COUNT(*) FROM productos WHERE visible = 1")->fetchColumn();
    }

    // Método para obtener un producto por ID
    public function obtenerProductoPorId($id) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para agregar un nuevo producto
    public function agregarProducto($nombre, $descripcion, $precio, $imagen) {
        global $pdo;

        $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, precio, imagen) VALUES (:nombre, :descripcion, :precio, :imagen)");
        return $stmt->execute(['nombre' => $nombre, 'descripcion' => $descripcion, 'precio' => $precio, 'imagen' => $imagen]);
    }

    // Método para editar un producto
    public function editarProducto($id, $nombre, $descripcion, $precio, $imagen) {
        global $pdo;

        $stmt = $pdo->prepare("UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, imagen = :imagen WHERE id = :id");
        return $stmt->execute(['id' => $id, 'nombre' => $nombre, 'descripcion' => $descripcion, 'precio' => $precio, 'imagen' => $imagen]);
    }

    // Método para eliminar un producto
    public function eliminarProducto($id) {
        global $pdo;

        $stmt = $pdo->prepare("DELETE FROM productos WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>
