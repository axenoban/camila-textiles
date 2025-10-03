<?php
// inventario.php

require_once __DIR__ . '/../models/inventario.php';

class InventarioController {

    public function listarInventario() {
        $inventarioModel = new Inventario();
        $inventario = $inventarioModel->obtenerInventario();
        include('views/admin/inventario.php');
    }

    public function agregarProductoInventario() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_producto = $_POST['id_producto'];
            $cantidad = $_POST['cantidad'];

            $inventarioModel = new Inventario();
            $inventarioModel->agregarAlInventario($id_producto, $cantidad);
            header('Location: /admin/inventario');
        }
        include('views/admin/agregar_inventario.php');
    }

    public function eliminarProductoInventario($id) {
        $inventarioModel = new Inventario();
        $inventarioModel->eliminarDelInventario($id);
        header('Location: /admin/inventario');
    }
}
?>
