<?php
require_once __DIR__ . '/../database/conexion.php';

class Pedido
{
    // 🧾 Obtener todos los pedidos de un cliente (sin agrupar)
    public function obtenerPedidosPorCliente($idUsuario)
    {
        global $pdo;
        $sql = "
            SELECT 
                ped.id,
                ped.id_producto,
                ped.id_color,
                p.nombre AS producto,
                c.nombre_color AS color,
                c.codigo_color,
                c.codigo_hex,
                ped.unidad,
                ped.cantidad,
                ped.precio_unitario,
                ped.total,
                ped.estado,
                ped.fecha_creacion
            FROM pedidos ped
            INNER JOIN productos p ON ped.id_producto = p.id
            INNER JOIN producto_colores c ON ped.id_color = c.id
            WHERE ped.id_usuario = :idUsuario
            ORDER BY ped.fecha_creacion DESC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['idUsuario' => $idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ➕ Crear un registro en pedidos (1 fila por color)
    public function crearPedido(array $data)
    {
        global $pdo;
        $sql = "
            INSERT INTO pedidos 
                (id_usuario, id_producto, id_color, unidad, cantidad, precio_unitario, total)
            VALUES 
                (:id_usuario, :id_producto, :id_color, :unidad, :cantidad, :precio_unitario, :total)
        ";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'id_usuario'      => $data['id_usuario'],
            'id_producto'     => $data['id_producto'],
            'id_color'        => $data['id_color'],
            'unidad'          => $data['unidad'],
            'cantidad'        => $data['cantidad'],
            'precio_unitario' => $data['precio_unitario'],
            'total'           => $data['total']
        ]);
    }

// Método para actualizar el estado y motivo de cancelación
public function actualizarEstado($idPedido, $nuevoEstado, $motivoCancelacion = null)
{
    global $pdo;

    // Actualización de estado y motivo de cancelación (si es cancelación)
    $sql = "UPDATE pedidos SET estado = :estado";
    $params = ['estado' => $nuevoEstado, 'id' => $idPedido];

    // Si es una cancelación, actualizar también el motivo
    if ($nuevoEstado === 'cancelado' && $motivoCancelacion) {
        $sql .= ", motivo_cancelacion = :motivo_cancelacion";
        $params['motivo_cancelacion'] = $motivoCancelacion;
    }

    $sql .= " WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute($params);
}

// Método para actualizar el estado de todos los pedidos agrupados
public function actualizarEstadoAgrupado($idPedido, $nuevoEstado)
{
    global $pdo;
    $sql = "
        UPDATE pedidos 
        SET estado = :estado
        WHERE id_usuario = (
            SELECT id_usuario FROM (SELECT id_usuario FROM pedidos WHERE id = :idPedido LIMIT 1) AS t1
        )
          AND id_producto = (
            SELECT id_producto FROM (SELECT id_producto FROM pedidos WHERE id = :idPedido LIMIT 1) AS t2
        )
          AND DATE(fecha_creacion) = (
            SELECT DATE(fecha_creacion) FROM (SELECT fecha_creacion FROM pedidos WHERE id = :idPedido LIMIT 1) AS t3
        )
    ";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'estado'   => $nuevoEstado,
        'idPedido' => $idPedido
    ]);
}





    // 📊 Contar cantidad total de pedidos de un producto
    public function contarPedidosPorProducto($idProducto)
    {
        global $pdo;
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM pedidos WHERE id_producto = ?");
            $stmt->execute([$idProducto]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error al contar pedidos: " . $e->getMessage());
            return 0;
        }
    }

    // 📋 Obtener todos los pedidos (sin agrupar) para admin
    public function obtenerTodosLosPedidos()
    {
        global $pdo;
        $sql = "
            SELECT 
                ped.id,
                u.nombre AS cliente,
                p.nombre AS producto,
                c.nombre_color AS color,
                c.codigo_color,
                c.codigo_hex,
                ped.unidad,
                ped.cantidad,
                ped.precio_unitario,
                ped.total,
                ped.estado,
                ped.fecha_creacion
            FROM pedidos ped
            INNER JOIN usuarios u ON ped.id_usuario = u.id
            INNER JOIN productos p ON ped.id_producto = p.id
            INNER JOIN producto_colores c ON ped.id_color = c.id
            ORDER BY ped.fecha_creacion DESC
        ";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 📦 Obtener pedidos agrupados (para administrador) por día
    public function obtenerPedidosAgrupados()
    {
        global $pdo;
        $sql = "
            SELECT 
                MIN(ped.id) AS id_pedido,                      -- representante del grupo
                u.nombre AS cliente,
                p.nombre AS producto,
                ped.unidad,
                GROUP_CONCAT(c.nombre_color ORDER BY c.nombre_color SEPARATOR ', ') AS colores,
                GROUP_CONCAT(c.codigo_color ORDER BY c.nombre_color SEPARATOR ',') AS codigos_color,
                GROUP_CONCAT(c.codigo_hex ORDER BY c.nombre_color SEPARATOR ',') AS codigos_hex,
                SUM(ped.cantidad) AS cantidad_total,
                SUM(ped.total) AS total_pedido,
                ped.estado,
                DATE(ped.fecha_creacion) AS fecha_pedido,
                MAX(ped.fecha_creacion) AS fecha_creacion
            FROM pedidos ped
            INNER JOIN usuarios u ON ped.id_usuario = u.id
            INNER JOIN productos p ON ped.id_producto = p.id
            INNER JOIN producto_colores c ON ped.id_color = c.id
            GROUP BY ped.id_usuario, ped.id_producto, ped.unidad, ped.estado, DATE(ped.fecha_creacion)
            ORDER BY fecha_creacion DESC
        ";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 📋 Obtener detalle agrupado por pedido (para cliente)
    public function obtenerDetalleAgrupado($idUsuario, $idPedido)
    {
        global $pdo;

        // Base: producto y fecha (día) del pedido representativo
        $sqlBase = "
            SELECT id_producto, DATE(fecha_creacion) AS fecha
            FROM pedidos
            WHERE id = :idPedido AND id_usuario = :idUsuario
            LIMIT 1
        ";
        $stmtBase = $pdo->prepare($sqlBase);
        $stmtBase->execute(['idPedido' => $idPedido, 'idUsuario' => $idUsuario]);
        $base = $stmtBase->fetch(PDO::FETCH_ASSOC);

        if (!$base) {
            return null;
        }

        $sql = "
            SELECT 
                MIN(ped.id) AS id_pedido,
                p.nombre AS producto,
                ped.unidad,
                GROUP_CONCAT(DISTINCT c.nombre_color ORDER BY c.nombre_color SEPARATOR ', ') AS colores,
                GROUP_CONCAT(DISTINCT c.codigo_color ORDER BY c.nombre_color SEPARATOR ',') AS codigos_color,
                GROUP_CONCAT(DISTINCT c.codigo_hex ORDER BY c.nombre_color SEPARATOR ',') AS codigos_hex,
                SUM(ped.cantidad) AS cantidad_total,
                ped.precio_unitario,
                SUM(ped.total) AS total_pedido,
                ped.estado,
                MAX(ped.fecha_creacion) AS fecha_creacion
            FROM pedidos ped
            INNER JOIN productos p ON ped.id_producto = p.id
            INNER JOIN producto_colores c ON ped.id_color = c.id
            WHERE ped.id_usuario = :idUsuario
              AND ped.id_producto = :idProducto
              AND DATE(ped.fecha_creacion) = :fecha
            GROUP BY ped.id_usuario, ped.id_producto, ped.unidad, ped.estado, ped.precio_unitario
            LIMIT 1
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'idUsuario'  => $idUsuario,
            'idProducto' => $base['id_producto'],
            'fecha'      => $base['fecha']
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🔍 Obtener detalle agrupado completo para administrador (por día)
    public function obtenerDetalleAgrupadoAdmin($idPedido)
    {
        global $pdo;
        $sql = "
            SELECT 
                ped.id,
                ped.id_usuario,
                ped.id_producto,
                u.nombre AS cliente,
                p.nombre AS producto,
                ped.unidad,
                c.nombre_color,
                c.codigo_color,
                c.codigo_hex,
                ped.cantidad,
                ped.precio_unitario,
                ped.total,
                ped.estado,
                ped.fecha_creacion
            FROM pedidos ped
            INNER JOIN usuarios u ON ped.id_usuario = u.id
            INNER JOIN productos p ON ped.id_producto = p.id
            INNER JOIN producto_colores c ON ped.id_color = c.id
            WHERE ped.id_usuario = (
                SELECT id_usuario FROM pedidos WHERE id = :idPedido LIMIT 1
            )
              AND ped.id_producto = (
                SELECT id_producto FROM pedidos WHERE id = :idPedido LIMIT 1
              )
              AND DATE(ped.fecha_creacion) = (
                SELECT DATE(fecha_creacion) FROM pedidos WHERE id = :idPedido LIMIT 1
              )
            ORDER BY c.nombre_color ASC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['idPedido' => $idPedido]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // 🔍 Obtener pedido simple
    public function obtenerPedidoPorId($idPedido)
    {
        global $pdo;
        $sql = "
            SELECT 
                ped.*, 
                p.nombre AS producto,
                c.nombre_color AS color,
                c.codigo_color,
                c.codigo_hex
            FROM pedidos ped
            INNER JOIN productos p ON ped.id_producto = p.id
            INNER JOIN producto_colores c ON ped.id_color = c.id
            WHERE ped.id = :idPedido
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['idPedido' => $idPedido]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 📦 Pedidos agrupados para CLIENTE (no mezclar días distintos)
    public function obtenerPedidosAgrupadosPorCliente($idUsuario)
    {
        global $pdo;
        $sql = "
            SELECT 
                MIN(ped.id) AS id_pedido,                  -- representante del grupo
                p.nombre AS producto,
                ped.unidad,
                GROUP_CONCAT(c.nombre_color ORDER BY c.nombre_color SEPARATOR ', ') AS colores,
                GROUP_CONCAT(c.codigo_color ORDER BY c.nombre_color SEPARATOR ',') AS codigos_color,
                GROUP_CONCAT(c.codigo_hex ORDER BY c.nombre_color SEPARATOR ',') AS codigos_hex,
                SUM(ped.cantidad) AS cantidad_total,
                SUM(ped.total) AS total_pedido,
                ped.estado,
                DATE(ped.fecha_creacion) AS fecha_pedido,
                MAX(ped.fecha_creacion) AS fecha_creacion
            FROM pedidos ped
            INNER JOIN productos p ON ped.id_producto = p.id
            INNER JOIN producto_colores c ON ped.id_color = c.id
            WHERE ped.id_usuario = :idUsuario
            GROUP BY ped.id_usuario, ped.id_producto, ped.unidad, ped.estado, DATE(ped.fecha_creacion)
            ORDER BY fecha_creacion DESC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['idUsuario' => $idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 💰 Calcular el total según unidad (metro o rollo)
    public function calcularTotalPedido($idProducto, $cantidad, $unidad)
    {
        $producto = $this->obtenerProductoPorId($idProducto);
        if (!$producto) {
            return 0;
        }

        $precioMetro     = (float)$producto['precio_metro'];
        $precioRollo     = (float)$producto['precio_rollo'];
        $metrosPorRollo  = max(1.0, (float)$producto['metros_por_rollo']);
        $precioRolloReal = $precioRollo * $metrosPorRollo;       // ✅ precio real por rollo

        if ($unidad === 'rollo') {
            // cantidad = número de rollos
            return $precioRolloReal * (float)$cantidad;
        }
        // unidad = metro
        return $precioMetro * (float)$cantidad;
    }

    // 📦 Producto por ID
    public function obtenerProductoPorId($idProducto)
    {
        global $pdo;
        $sql = "SELECT * FROM productos WHERE id = :idProducto";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['idProducto' => $idProducto]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🎨 Color por ID
    public function obtenerColorPorId($idColor)
    {
        global $pdo;
        $sql = "SELECT * FROM producto_colores WHERE id = :idColor";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['idColor' => $idColor]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // 📦 Obtener pedidos agrupados por ID de pedido (para cancelación)
    public function obtenerPedidosAgrupadosPorPedido($idPedido)
{
    global $pdo;
    $sql = "
        SELECT 
            ped.id AS id_pedido,                      -- representante del grupo
            p.nombre AS producto,
            ped.unidad,
            GROUP_CONCAT(c.nombre_color ORDER BY c.nombre_color SEPARATOR ', ') AS colores,
            GROUP_CONCAT(c.codigo_color ORDER BY c.nombre_color SEPARATOR ',') AS codigos_color,
            GROUP_CONCAT(c.codigo_hex ORDER BY c.nombre_color SEPARATOR ',') AS codigos_hex,
            SUM(ped.cantidad) AS cantidad_total,
            SUM(ped.total) AS total_pedido,
            ped.estado,
            DATE(ped.fecha_creacion) AS fecha_pedido,
            MAX(ped.fecha_creacion) AS fecha_creacion
        FROM pedidos ped
        INNER JOIN productos p ON ped.id_producto = p.id
        INNER JOIN producto_colores c ON ped.id_color = c.id
        WHERE ped.id = :idPedido
        GROUP BY ped.id_usuario, ped.id_producto, ped.unidad, ped.estado, DATE(ped.fecha_creacion)
        ORDER BY fecha_creacion DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['idPedido' => $idPedido]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
