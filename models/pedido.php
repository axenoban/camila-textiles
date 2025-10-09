<?php
require_once __DIR__ . '/../database/conexion.php';

class Pedido
{
    // 🔹 Obtener todos los pedidos (vista para admin)
    public function obtenerTodosLosPedidos()
    {
        global $pdo;
        $sql = "
            SELECT 
                p.id,
                u.nombre AS cliente,
                pr.nombre AS producto,
                pp.tipo AS presentacion,
                pc.nombre AS color,
                p.cantidad,
                p.total,
                p.estado
            FROM pedidos p
            INNER JOIN usuarios u ON p.id_usuario = u.id
            INNER JOIN productos pr ON p.id_producto = pr.id
            LEFT JOIN producto_presentaciones pp ON p.id_presentacion = pp.id
            LEFT JOIN producto_colores pc ON p.id_color = pc.id
            ORDER BY p.fecha_creacion DESC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Obtener pedidos de un cliente específico (para vista cliente)
    public function obtenerPedidosPorCliente($idUsuario)
    {
        global $pdo;
        $sql = "
            SELECT 
                p.id,
                pr.nombre AS producto,
                pc.nombre AS color,
                pp.tipo AS presentacion,
                p.cantidad,
                p.precio_unitario,
                p.total,
                p.estado,
                p.fecha_creacion
            FROM pedidos p
            INNER JOIN productos pr ON p.id_producto = pr.id
            LEFT JOIN producto_presentaciones pp ON p.id_presentacion = pp.id
            LEFT JOIN producto_colores pc ON p.id_color = pc.id
            WHERE p.id_usuario = :idUsuario
            ORDER BY p.fecha_creacion DESC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['idUsuario' => $idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Cambiar el estado de un pedido (confirmar o cancelar)
    public function actualizarEstado($idPedido, $nuevoEstado)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            UPDATE pedidos 
            SET estado = :estado 
            WHERE id = :id
        ");
        return $stmt->execute([
            'estado' => $nuevoEstado,
            'id' => $idPedido
        ]);
    }
}
?>
