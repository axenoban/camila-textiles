<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../database/conexion.php';

$accion = $_POST['accion'] ?? $_GET['accion'] ?? null;

switch ($accion) {

    case 'crear':
        $sql = "INSERT INTO producto_colores 
                (id_producto, nombre_color, codigo_color, codigo_hex, stock_metros, stock_rollos, imagen_muestra, estado)
                VALUES (:id_producto, :nombre_color, :codigo_color, :codigo_hex, :stock_metros, :stock_rollos, :imagen_muestra, :estado)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id_producto' => $_POST['id_producto'],
            'nombre_color' => $_POST['nombre_color'],
            'codigo_color' => $_POST['codigo_color'] ?: null,
            'codigo_hex' => $_POST['codigo_hex'],
            'stock_metros' => $_POST['stock_metros'],
            'stock_rollos' => $_POST['stock_rollos'],
            'imagen_muestra' => $_POST['imagen_muestra'] ?: null,
            'estado' => $_POST['estado'],
        ]);
        header('Location: ' . BASE_URL . '/views/admin/colores_producto.php?id=' . $_POST['id_producto']);
        break;

    case 'eliminar':
        $id = (int)$_GET['id'];
        $idProducto = (int)$_GET['id_producto'];
        $stmt = $pdo->prepare("DELETE FROM producto_colores WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: ' . BASE_URL . '/views/admin/colores_producto.php?id=' . $idProducto);
        break;

    default:
        header('Location: ' . BASE_URL . '/views/admin/productos.php?status=error');
        break;
}
?>
