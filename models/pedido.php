<?php
// pedido.php

require_once __DIR__ . '/../database/conexion.php';

class Pedido {

    private function adjuntarDetalles(array $pedidos) {
        if (empty($pedidos)) {
            return [];
        }

        global $pdo;

        $ids = array_map(static fn($pedido) => (int) ($pedido['id'] ?? 0), $pedidos);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql = "
            SELECT
                pd.id,
                pd.id_pedido,
                pd.id_color,
                pd.id_presentacion,
                pd.cantidad,
                pd.unidad,
                pd.precio_unitario,
                pd.subtotal,
                pc.nombre AS color_nombre,
                pc.codigo_hex,
                pp.tipo AS presentacion_tipo,
                pp.metros_por_unidad
            FROM pedido_detalles pd
            INNER JOIN producto_colores pc ON pd.id_color = pc.id
            INNER JOIN producto_presentaciones pp ON pd.id_presentacion = pp.id
            WHERE pd.id_pedido IN ($placeholders)
            ORDER BY pd.id
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($ids);
        $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $porPedido = [];
        foreach ($detalles as $detalle) {
            $pedidoId = (int) ($detalle['id_pedido'] ?? 0);
            $detalle['cantidad'] = (float) ($detalle['cantidad'] ?? 0);
            $detalle['precio_unitario'] = (float) ($detalle['precio_unitario'] ?? 0);
            $detalle['subtotal'] = (float) ($detalle['subtotal'] ?? 0);
            $detalle['metros_por_unidad'] = $detalle['metros_por_unidad'] !== null
                ? (float) $detalle['metros_por_unidad']
                : null;
            $porPedido[$pedidoId][] = $detalle;
        }

        foreach ($pedidos as &$pedido) {
            $pedidoId = (int) ($pedido['id'] ?? 0);
            $pedido['detalles'] = $porPedido[$pedidoId] ?? [];
        }

        return $pedidos;
    }

    public function obtenerPedidosPorCliente($idUsuario) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT p.*, pr.nombre AS producto, pr.precio FROM pedidos p INNER JOIN productos pr ON p.id_producto = pr.id WHERE p.id_usuario = :id_usuario ORDER BY p.fecha_creacion DESC");
        $stmt->execute(['id_usuario' => (int) $idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->adjuntarDetalles($pedidos);
    }

    public function obtenerPedidosConDetalles($limite = null) {
        $pedidos = $this->obtenerTodosLosPedidos();

        if ($limite !== null) {
            return array_slice($pedidos, 0, (int) $limite);
        }

        return $pedidos;
    }

    public function contarPedidosPorEstado($estado) {
        global $pdo;

        $stmt = $pdo->prepare('SELECT COUNT(*) FROM pedidos WHERE estado = :estado');
        $stmt->execute(['estado' => $estado]);
        return (int) $stmt->fetchColumn();
    }

    public function calcularIngresosTotales() {
        global $pdo;

        $stmt = $pdo->query("SELECT COALESCE(SUM(p.total), 0) FROM pedidos p WHERE p.estado IN ('pendiente', 'confirmado', 'completado')");
        return (float) $stmt->fetchColumn();
    }

    public function confirmarPedido($id) {
        global $pdo;

        $stmt = $pdo->prepare("UPDATE pedidos SET estado = 'confirmado' WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function completarPedido($id) {
        global $pdo;

        $stmt = $pdo->prepare("UPDATE pedidos SET estado = 'completado' WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function cancelarPedido($id) {
        global $pdo;

        $stmt = $pdo->prepare("UPDATE pedidos SET estado = 'cancelado' WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function crearPedido($idUsuario, $idProducto, $cantidad) {
        global $pdo;

        $stmt = $pdo->prepare("INSERT INTO pedidos (id_usuario, id_producto, cantidad, estado) VALUES (:id_usuario, :id_producto, :cantidad, 'pendiente')");

        return $stmt->execute([
            'id_usuario' => (int) $idUsuario,
            'id_producto' => (int) $idProducto,
            'cantidad' => (int) $cantidad,
        ]);
    }
}
?>
