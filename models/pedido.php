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
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->adjuntarDetalles($pedidos);
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

    public function crearPedido($idUsuario, $idProducto, $total) {
        global $pdo;

        $stmt = $pdo->prepare("INSERT INTO pedidos (id_usuario, id_producto, total, estado) VALUES (:id_usuario, :id_producto, :total, 'pendiente')");

        $exito = $stmt->execute([
            'id_usuario' => (int) $idUsuario,
            'id_producto' => (int) $idProducto,
            'total' => (float) $total,
        ]);

        if (!$exito) {
            return 0;
        }

        return (int) $pdo->lastInsertId();
    }

    public function agregarDetalle($idPedido, $idColor, $idPresentacion, $cantidad, $unidad, $precioUnitario, $subtotal) {
        global $pdo;

        $stmt = $pdo->prepare("INSERT INTO pedido_detalles (id_pedido, id_color, id_presentacion, cantidad, unidad, precio_unitario, subtotal) VALUES (:id_pedido, :id_color, :id_presentacion, :cantidad, :unidad, :precio_unitario, :subtotal)");

        return $stmt->execute([
            'id_pedido' => (int) $idPedido,
            'id_color' => (int) $idColor,
            'id_presentacion' => (int) $idPresentacion,
            'cantidad' => (float) $cantidad,
            'unidad' => $unidad,
            'precio_unitario' => (float) $precioUnitario,
            'subtotal' => (float) $subtotal,
        ]);
    }
}
?>
