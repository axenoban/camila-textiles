<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/pedido.php';
require_once __DIR__ . '/../models/producto.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar login cliente
if (empty($_SESSION['usuario']) || ($_SESSION['usuario']['rol'] ?? '') !== 'cliente') {
    header('Location: ' . BASE_URL . '/views/public/login.php');
    exit;
}

$pedidoModel = new Pedido();
$productoModel = new Producto();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = (int)$_SESSION['usuario']['id'];
    $idProducto = filter_input(INPUT_POST, 'id_producto', FILTER_VALIDATE_INT);

    // Normalizar colores seleccionados
    $coloresSeleccionados = $_POST['id_color'] ?? [];
    if (!is_array($coloresSeleccionados)) {
        $coloresSeleccionados = [$coloresSeleccionados];
    }

    $unidad   = $_POST['unidad'] ?? '';
    $cantidad = (float)($_POST['cantidad'] ?? 0);

    // Validación básica
    if (!$idProducto || empty($coloresSeleccionados) || $cantidad <= 0 || !in_array($unidad, ['metro', 'rollo'], true)) {
        $_SESSION['reserva_mensaje'] = '⚠️ Debes seleccionar al menos un color y una cantidad válida.';
        header('Location: ' . BASE_URL . '/views/cliente/detalle_producto_cliente.php?id=' . (int)$idProducto);
        exit;
    }

    // Obtener producto
    $producto = $productoModel->obtenerProductoPorId($idProducto);
    if (!$producto) {
        $_SESSION['reserva_mensaje'] = 'El producto seleccionado no existe.';
        header('Location: ' . BASE_URL . '/views/cliente/productos.php');
        exit;
    }

    // Parametrización de precios
    $precioMetro        = (float)$producto['precio_metro'];
    $precioRollo        = (float)$producto['precio_rollo'];
    $metrosPorRollo     = max(1.0, (float)$producto['metros_por_rollo']); // evitar división por cero
    $precioRolloReal    = $precioRollo * $metrosPorRollo;  // ✅ precio por rollo real
    $precioUnitarioBase = ($unidad === 'metro') ? $precioMetro : $precioRolloReal;

    // Validar colores y crear 1 fila por color
    $insertados = 0;

    foreach ($coloresSeleccionados as $idColor) {
        $idColor = (int)$idColor;
        if ($idColor <= 0) { continue; }

        // Verificar color existe y pertenece al producto
        $color = $pedidoModel->obtenerColorPorId($idColor);
        if (!$color || (int)$color['id_producto'] !== (int)$idProducto) {
            $_SESSION['reserva_mensaje'] = '⚠️ Uno de los colores seleccionados no existe o no corresponde a este producto.';
            header('Location: ' . BASE_URL . '/views/cliente/detalle_producto_cliente.php?id=' . (int)$idProducto);
            exit;
        }

        if (($color['estado'] ?? 'disponible') !== 'disponible') {
            $_SESSION['reserva_mensaje'] = '⚠️ El color seleccionado no está disponible en stock.';
            header('Location: ' . BASE_URL . '/views/cliente/detalle_producto_cliente.php?id=' . (int)$idProducto);
            exit;
        }

        // Calcular precio unitario / total por color
        $precioUnitario = $precioUnitarioBase;                // metro: precio_metro | rollo: precio_rollo * metros_por_rollo
        $total          = $precioUnitario * $cantidad;

        // Insertar fila en pedidos (1 por color)
        $pedidoModel->crearPedido([
            'id_usuario'      => $idUsuario,
            'id_producto'     => $idProducto,
            'id_color'        => $idColor,
            'unidad'          => $unidad,
            'cantidad'        => $cantidad,
            'precio_unitario' => $precioUnitario,
            'total'           => $total
        ]);

        $insertados++;
    }

    // Mensaje de éxito
    $_SESSION['reserva_mensaje'] = "✅ Pedido registrado correctamente para {$insertados} color" . ($insertados > 1 ? 'es' : '') . '.';
    header('Location: ' . BASE_URL . '/views/cliente/detalle_producto_cliente.php?id=' . (int)$idProducto);
    exit;
}
