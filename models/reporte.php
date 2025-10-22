<?php
require_once __DIR__ . '/../database/conexion.php';

class Reporte {

    // 📊 MÉTRICAS GENERALES DEL SISTEMA
    public function obtenerMetricasGenerales() {
        global $pdo;

        $sql = "
            SELECT
                (SELECT COUNT(*) FROM productos WHERE visible = 1) AS total_productos,
                (SELECT COUNT(*) FROM pedidos) AS total_pedidos,
                (SELECT COUNT(*) FROM usuarios WHERE rol = 'cliente') AS total_clientes,
                COALESCE(SUM(p.total), 0) AS total_ingresos
            FROM pedidos p
        ";

        $stmt = $pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🧾 PEDIDOS RECIENTES
    public function obtenerPedidosRecientes($limite = 5) {
        global $pdo;

        $sql = "
            SELECT 
                ped.id,
                ped.fecha_creacion,
                ped.estado,
                ped.cantidad,
                ped.total,
                u.nombre AS cliente,
                pr.nombre AS producto,
                c.nombre_color AS color,
                c.codigo_hex AS codigo_hex
            FROM pedidos ped
            INNER JOIN usuarios u ON ped.id_usuario = u.id
            INNER JOIN productos pr ON ped.id_producto = pr.id
            INNER JOIN producto_colores c ON ped.id_color = c.id
            ORDER BY ped.fecha_creacion DESC
            LIMIT :limite
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ⚠️ PRODUCTOS CON BAJO STOCK
    public function obtenerProductosConBajoStock($umbral = 10) {
        global $pdo;

        $sql = "
            SELECT 
                pr.id AS id_producto,
                pr.nombre AS producto,
                c.nombre_color AS color,
                c.codigo_hex AS codigo_hex,
                c.stock_metros,
                c.stock_rollos
            FROM producto_colores c
            INNER JOIN productos pr ON c.id_producto = pr.id
            WHERE (c.stock_metros < :umbral OR c.stock_rollos < :umbral)
            ORDER BY c.stock_metros ASC, c.stock_rollos ASC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':umbral', (int)$umbral, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 📅 INGRESOS MENSUALES
    public function obtenerIngresosMensuales() {
        global $pdo;

        $sql = "
            SELECT 
                DATE_FORMAT(fecha_creacion, '%Y-%m') AS mes,
                SUM(total) AS total_mensual
            FROM pedidos
            WHERE estado IN ('confirmado','completado')
            GROUP BY mes
            ORDER BY mes DESC
            LIMIT 6
        ";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
