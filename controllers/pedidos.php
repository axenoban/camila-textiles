<?php
// pedidos.php

require_once __DIR__ . '/../models/pedido.php';

class PedidosController {

    public function listarPedidosCliente() {
        // Obtiene los pedidos de un cliente desde la base de datos
        $pedidoModel = new Pedido();
        $pedidos = $pedidoModel->obtenerPedidosPorCliente($_SESSION['usuario']);
        include('views/cliente/pedidos.php');
    }

    public function listarPedidosAdmin() {
        // Lista todos los pedidos para administración
        $pedidoModel = new Pedido();
        $pedidos = $pedidoModel->obtenerTodosLosPedidos();
        include('views/admin/pedidos.php');
    }

    public function confirmarReserva($id) {
        $pedidoModel = new Pedido();
        $pedidoModel->confirmarPedido($id);
        header('Location: /admin/pedidos');
    }
}
?>
