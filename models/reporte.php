<?php
// reporte.php

require_once __DIR__ . '/../database/conexion.php';

class Reporte {

    public function obtenerMetricasGenerales() {
        global $pdo;

        return [
            'productos' => (int) $pdo->query("SELECT COUNT(*) FROM productos WHERE visible = 1")->fetchColumn(),
            'clientes' => (int) $pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'cliente'")->fetchColumn(),
            'pedidosPendientes' => (int) $pdo->query("SELECT COUNT(*) FROM pedidos WHERE estado = 'pendiente'")->fetchColumn(),
            'ingresos' => (float) $pdo->query("SELECT COALESCE(SUM(pr.precio * p.cantidad), 0) FROM pedidos p INNER JOIN productos pr ON p.id_producto = pr.id WHERE p.estado IN ('pendiente', 'confirmado', 'completado')")->fetchColumn(),
            'ventasCompletadas' => (int) $pdo->query("SELECT COUNT(*) FROM pedidos WHERE estado = 'completado'")->fetchColumn(),
            'stockDisponible' => (int) $pdo->query('SELECT COALESCE(SUM(cantidad), 0) FROM inventarios')->fetchColumn(),
        ];
    }

    public function obtenerPedidosRecientes($limite = 5) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT p.id, u.nombre AS cliente, pr.nombre AS producto, p.estado, p.cantidad, p.fecha_creacion FROM pedidos p INNER JOIN usuarios u ON p.id_usuario = u.id INNER JOIN productos pr ON p.id_producto = pr.id ORDER BY p.fecha_creacion DESC LIMIT :limite");
        $stmt->bindValue(':limite', (int) $limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProductosConBajoStock($umbral = 25) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT pr.nombre, i.cantidad FROM inventarios i INNER JOIN productos pr ON i.id_producto = pr.id WHERE i.cantidad <= :umbral ORDER BY i.cantidad ASC");
        $stmt->bindValue(':umbral', (int) $umbral, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
