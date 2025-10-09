<?php
// productos.php

require_once 'models/producto.php';

class ProductosController {

    public function mostrarCatalogo() {
        $productoModel = new Producto();
        $productos = $productoModel->obtenerProductosVisibles();
        include('views/public/productos.php');
    }

    public function agregarProducto() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = trim($_POST['nombre']);
            $descripcion = trim($_POST['descripcion']);
            $color = trim($_POST['color']);
            $unidadVenta = $_POST['unidad_venta'] ?? 'metro';
            $unidadVenta = in_array($unidadVenta, ['metro', 'rollo'], true) ? $unidadVenta : 'metro';
            $precio = (float) $_POST['precio'];
            $imagen = trim($_POST['imagen']);  // Usaremos una URL externa para la imagen

            $productoModel = new Producto();
            $productoModel->agregarProducto($nombre, $descripcion, $color, $unidadVenta, $precio, $imagen);
            header('Location: /admin/productos');
        }
        include('views/admin/agregar_producto.php');
    }

    public function editarProducto($id) {
        $productoModel = new Producto();
        $producto = $productoModel->obtenerProductoPorId($id);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = trim($_POST['nombre']);
            $descripcion = trim($_POST['descripcion']);
            $color = trim($_POST['color']);
            $unidadVenta = $_POST['unidad_venta'] ?? 'metro';
            $unidadVenta = in_array($unidadVenta, ['metro', 'rollo'], true) ? $unidadVenta : 'metro';
            $precio = (float) $_POST['precio'];
            $imagen = trim($_POST['imagen']);  // Usaremos una URL externa para la imagen

            $productoModel->editarProducto($id, $nombre, $descripcion, $color, $unidadVenta, $precio, $imagen);
            header('Location: /admin/productos');
        }

        include('views/admin/editar_producto.php');
    }

    public function eliminarProducto($id) {
        $productoModel = new Producto();
        $productoModel->eliminarProducto($id);
        header('Location: /admin/productos');
    }
}
?>
