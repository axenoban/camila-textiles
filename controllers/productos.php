<?php
// productos.php

require_once __DIR__ . '/../models/producto.php';

class ProductosController {

    public function mostrarCatalogo() {
        $productoModel = new Producto();
        $productos = $productoModel->obtenerProductosVisibles();
        include('views/public/productos.php');
    }

    public function agregarProducto() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $imagen = $_POST['imagen'];  // Usaremos una URL externa para la imagen

            $productoModel = new Producto();
            $productoModel->agregarProducto($nombre, $descripcion, $precio, $imagen);
            header('Location: /admin/productos');
        }
        include('views/admin/agregar_producto.php');
    }

    public function editarProducto($id) {
        $productoModel = new Producto();
        $producto = $productoModel->obtenerProductoPorId($id);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $imagen = $_POST['imagen'];  // Usaremos una URL externa para la imagen

            $productoModel->editarProducto($id, $nombre, $descripcion, $precio, $imagen);
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
