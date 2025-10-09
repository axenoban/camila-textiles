<?php
// producto.php

require_once __DIR__ . '/../database/conexion.php';

class Producto {

    // Método para obtener todos los productos visibles
    public function obtenerProductosVisibles() {
        global $pdo;

        $sql = "
            SELECT 
                p.*, 
                COALESCE(variantes.stock_total, i.cantidad, 0) AS stock,
                COALESCE(precios.precio_desde, p.precio) AS precio_desde,
                COALESCE(colores.total_colores, 0) AS total_colores
            FROM productos p
            LEFT JOIN (
                SELECT 
                    pe.id_producto,
                    SUM(
                        CASE 
                            WHEN pp.tipo = 'rollo' THEN pe.stock * COALESCE(pp.metros_por_unidad, 0)
                            ELSE pe.stock
                        END
                    ) AS stock_total
                FROM producto_existencias pe
                INNER JOIN producto_presentaciones pp ON pe.id_presentacion = pp.id
                GROUP BY pe.id_producto
            ) AS variantes ON variantes.id_producto = p.id
            LEFT JOIN inventarios i ON i.id_producto = p.id
            LEFT JOIN (
                SELECT id_producto, MIN(precio) AS precio_desde FROM producto_presentaciones GROUP BY id_producto
            ) AS precios ON precios.id_producto = p.id
            LEFT JOIN (
                SELECT id_producto, COUNT(*) AS total_colores FROM producto_colores GROUP BY id_producto
            ) AS colores ON colores.id_producto = p.id
            WHERE p.visible = 1
            ORDER BY p.fecha_creacion DESC
        ";

        $stmt = $pdo->prepare($sql);
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

        $sql = "
            SELECT 
                p.*, 
                COALESCE(variantes.stock_total, i.cantidad, 0) AS stock_total,
                COALESCE(precios.precio_desde, p.precio) AS precio_desde
            FROM productos p
            LEFT JOIN (
                SELECT 
                    pe.id_producto,
                    SUM(
                        CASE 
                            WHEN pp.tipo = 'rollo' THEN pe.stock * COALESCE(pp.metros_por_unidad, 0)
                            ELSE pe.stock
                        END
                    ) AS stock_total
                FROM producto_existencias pe
                INNER JOIN producto_presentaciones pp ON pe.id_presentacion = pp.id
                WHERE pe.id_producto = :id_producto
                GROUP BY pe.id_producto
            ) AS variantes ON variantes.id_producto = p.id
            LEFT JOIN inventarios i ON i.id_producto = p.id
            LEFT JOIN (
                SELECT id_producto, MIN(precio) AS precio_desde FROM producto_presentaciones WHERE id_producto = :id_producto GROUP BY id_producto
            ) AS precios ON precios.id_producto = p.id
            WHERE p.id = :id_producto
            LIMIT 1
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_producto' => (int) $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerColoresPorProducto($idProducto) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM producto_colores WHERE id_producto = :id_producto ORDER BY nombre ASC");
        $stmt->execute(['id_producto' => (int) $idProducto]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPresentacionesPorProducto($idProducto) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM producto_presentaciones WHERE id_producto = :id_producto ORDER BY FIELD(tipo, 'rollo', 'metro'), precio ASC");
        $stmt->execute(['id_producto' => (int) $idProducto]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerExistenciasPorProducto($idProducto) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT pe.*, pc.nombre AS color_nombre, pc.codigo_hex, pp.tipo, pp.metros_por_unidad FROM producto_existencias pe INNER JOIN producto_presentaciones pp ON pe.id_presentacion = pp.id INNER JOIN producto_colores pc ON pe.id_color = pc.id WHERE pe.id_producto = :id_producto");
        $stmt->execute(['id_producto' => (int) $idProducto]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerColorPorId($idColor) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM producto_colores WHERE id = :id");
        $stmt->execute(['id' => (int) $idColor]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPresentacionPorId($idPresentacion) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM producto_presentaciones WHERE id = :id");
        $stmt->execute(['id' => (int) $idPresentacion]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerStockVariante($idProducto, $idColor, $idPresentacion) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT stock FROM producto_existencias WHERE id_producto = :id_producto AND id_color = :id_color AND id_presentacion = :id_presentacion");
        $stmt->execute([
            'id_producto' => (int) $idProducto,
            'id_color' => (int) $idColor,
            'id_presentacion' => (int) $idPresentacion,
        ]);

        $stock = $stmt->fetchColumn();
        return $stock !== false ? (float) $stock : 0.0;
    }

    public function disminuirStockVariante($idProducto, $idColor, $idPresentacion, $cantidad) {
        global $pdo;

        $stmt = $pdo->prepare("UPDATE producto_existencias SET stock = GREATEST(stock - :cantidad, 0) WHERE id_producto = :id_producto AND id_color = :id_color AND id_presentacion = :id_presentacion");

        return $stmt->execute([
            'cantidad' => (float) $cantidad,
            'id_producto' => (int) $idProducto,
            'id_color' => (int) $idColor,
            'id_presentacion' => (int) $idPresentacion,
        ]);
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
