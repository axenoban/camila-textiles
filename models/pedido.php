<?php
// pedido.php

require_once __DIR__ . '/../database/conexion.php';

class Pedido {

    // Método para obtener los pedidos de un cliente
    public function obtenerPedidosPorCliente($idUsuario) {
        global $pdo;

        $sql = "
            SELECT 
                p.*, 
                pr.nombre AS producto,
                pr.imagen,
                pc.nombre AS color_nombre,
                pc.codigo_hex,
                pp.tipo AS presentacion_tipo,
                pp.metros_por_unidad
            FROM pedidos p
            INNER JOIN productos pr ON p.id_producto = pr.id
            LEFT JOIN producto_colores pc ON p.id_color = pc.id
            LEFT JOIN producto_presentaciones pp ON p.id_presentacion = pp.id
            WHERE p.id_usuario = :id_usuario
            ORDER BY p.fecha_creacion DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_usuario' => (int) $idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener todos los pedidos
    public function obtenerTodosLosPedidos() {
        global $pdo;

        $sql = "
            SELECT 
                p.*, 
                u.nombre AS cliente, 
                pr.nombre AS producto,
                pc.nombre AS color_nombre,
                pc.codigo_hex,
                pp.tipo AS presentacion_tipo,
                pp.metros_por_unidad
            FROM pedidos p
            INNER JOIN usuarios u ON p.id_usuario = u.id
            INNER JOIN productos pr ON p.id_producto = pr.id
            LEFT JOIN producto_colores pc ON p.id_color = pc.id
            LEFT JOIN producto_presentaciones pp ON p.id_presentacion = pp.id
            ORDER BY p.fecha_creacion DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener pedidos con detalles y límite configurable
    public function obtenerPedidosConDetalles($limite = null) {
        $pedidos = $this->obtenerTodosLosPedidos();

        if ($limite !== null) {
            return array_slice($pedidos, 0, (int) $limite);
        }

        return $pedidos;
    }

    // Método para contar pedidos por estado
    public function contarPedidosPorEstado($estado) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM pedidos WHERE estado = :estado");
        $stmt->execute(['estado' => $estado]);
        return (int) $stmt->fetchColumn();
    }

    // Método para calcular el ingreso estimado de los pedidos
    public function calcularIngresosTotales() {
        global $pdo;

        $stmt = $pdo->query("SELECT COALESCE(SUM(p.total), 0) FROM pedidos p WHERE p.estado IN ('pendiente', 'confirmado', 'completado')");
        return (float) $stmt->fetchColumn();
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

    public function crearPedido($idUsuario, $idProducto, $idColor, $idPresentacion, $cantidad, $unidad, $precioUnitario, $total) {
        global $pdo;

        $stmt = $pdo->prepare("INSERT INTO pedidos (id_usuario, id_producto, id_color, id_presentacion, cantidad, unidad, precio_unitario, total, estado) VALUES (:id_usuario, :id_producto, :id_color, :id_presentacion, :cantidad, :unidad, :precio_unitario, :total, 'pendiente')");

        return $stmt->execute([
            'id_usuario' => (int) $idUsuario,
            'id_producto' => (int) $idProducto,
            'id_color' => $idColor !== null ? (int) $idColor : null,
            'id_presentacion' => $idPresentacion !== null ? (int) $idPresentacion : null,
            'cantidad' => (float) $cantidad,
            'unidad' => $unidad,
            'precio_unitario' => (float) $precioUnitario,
            'total' => (float) $total,
        ]);
    }
}
?>
