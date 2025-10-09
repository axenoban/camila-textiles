<?php
require_once __DIR__ . '/../database/conexion.php';
session_start();

if (empty($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'cliente') {
    header('Location: ' . BASE_URL . '/views/public/login.php');
    exit;
}

$idUsuario = $_SESSION['usuario']['id'] ?? 0;
$idProducto = (int) ($_POST['producto_id'] ?? 0);
$idColor = !empty($_POST['color_id']) ? (int) $_POST['color_id'] : null;
$idPresentacion = !empty($_POST['presentacion_id']) ? (int) $_POST['presentacion_id'] : null;
$cantidad = (float) ($_POST['cantidad'] ?? 0);

if ($idUsuario && $idProducto && $cantidad > 0) {
    try {
        // Obtener precio unitario de producto/presentación
        $stmt = $pdo->prepare("SELECT precio FROM producto_presentaciones WHERE id = :id_presentacion");
        $stmt->execute(['id_presentacion' => $idPresentacion]);
        $precioUnitario = $stmt->fetchColumn() ?: 0;

        $total = $precioUnitario * $cantidad;

        $insert = $pdo->prepare("
            INSERT INTO pedidos 
            (id_usuario, id_producto, id_color, id_presentacion, cantidad, unidad, precio_unitario, total, estado) 
            VALUES 
            (:usuario, :producto, :color, :presentacion, :cantidad, 'metro', :precio, :total, 'pendiente')
        ");
        $insert->execute([
            'usuario' => $idUsuario,
            'producto' => $idProducto,
            'color' => $idColor,
            'presentacion' => $idPresentacion,
            'cantidad' => $cantidad,
            'precio' => $precioUnitario,
            'total' => $total
        ]);

        $_SESSION['reserva_mensaje'] = "Tu reserva fue registrada correctamente.";
        $_SESSION['reserva_tipo'] = 'success';

    } catch (PDOException $e) {
        error_log("Error al registrar pedido: " . $e->getMessage());
        $_SESSION['reserva_mensaje'] = "Error al registrar tu pedido. Intenta nuevamente.";
        $_SESSION['reserva_tipo'] = 'danger';
    }
}

header('Location: ' . BASE_URL . '/views/cliente/detalle_producto_cliente.php?id=' . $idProducto);
exit;
