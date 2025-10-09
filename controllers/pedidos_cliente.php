<?php
session_start();

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../database/conexion.php';
require_once __DIR__ . '/../models/pedido.php';
require_once __DIR__ . '/../models/producto.php';
require_once __DIR__ . '/../models/inventario.php';

if (!isset($_SESSION['usuario']) || ($_SESSION['rol'] ?? '') !== 'cliente') {
    header('Location: ' . BASE_URL . '/views/public/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/views/cliente/productos.php');
    exit;
}

$idUsuario = (int) ($_SESSION['usuario']['id'] ?? 0);
$idProducto = filter_input(INPUT_POST, 'producto_id', FILTER_VALIDATE_INT);
$itemsPayload = $_POST['line_items'] ?? $_POST['items'] ?? '[]';

if (is_array($itemsPayload)) {
    $itemsPayload = json_encode($itemsPayload);
}

$lineItems = json_decode((string) $itemsPayload, true);

$_SESSION['reserva_tipo'] = 'danger';
$_SESSION['reserva_mensaje'] = 'No fue posible registrar tu reserva. Revisa los datos ingresados.';

if (!$idProducto || !is_array($lineItems) || empty($lineItems)) {
    header('Location: ' . BASE_URL . '/views/cliente/detalle_producto.php?id=' . $idProducto);
    exit;
}

$productoModel = new Producto();
$producto = $productoModel->obtenerProductoPorId($idProducto);

if (!$producto || !(bool) ($producto['visible'] ?? true)) {
    $_SESSION['reserva_mensaje'] = 'El producto seleccionado no está disponible.';
    header('Location: ' . BASE_URL . '/views/cliente/productos.php');
    exit;
}

$inventarioModel = new Inventario();

$pedidoModel = new Pedido();

$agrupados = [];
$orden = [];

foreach ($lineItems as $item) {
    $colorId = isset($item['color_id']) ? (int) $item['color_id'] : 0;
    $presentacionId = isset($item['presentacion_id']) ? (int) $item['presentacion_id'] : 0;
    $cantidadSolicitada = isset($item['cantidad']) ? (float) $item['cantidad'] : 0.0;

    if ($colorId <= 0 || $presentacionId <= 0 || $cantidadSolicitada <= 0) {
        header('Location: ' . BASE_URL . '/views/cliente/detalle_producto.php?id=' . $idProducto);
        exit;
    }

    $clave = $colorId . '-' . $presentacionId;

    if (!isset($agrupados[$clave])) {
        $agrupados[$clave] = [
            'color_id' => $colorId,
            'presentacion_id' => $presentacionId,
            'cantidad' => 0.0,
        ];
        $orden[] = $clave;
    }

    $agrupados[$clave]['cantidad'] += round($cantidadSolicitada, 2);
}

$detallesConfirmados = [];
$totalPedido = 0.0;
$metrosComprometidos = 0.0;

foreach ($orden as $clave) {
    $entrada = $agrupados[$clave];
    $colorId = $entrada['color_id'];
    $presentacionId = $entrada['presentacion_id'];
    $cantidadSolicitada = round((float) $entrada['cantidad'], 2);

    if ($cantidadSolicitada <= 0) {
        $_SESSION['reserva_mensaje'] = 'Cada combinación debe superar las 0 unidades.';
        header('Location: ' . BASE_URL . '/views/cliente/detalle_producto.php?id=' . $idProducto);
        exit;
    }

    $color = $productoModel->obtenerColorPorId($colorId);
    $presentacion = $productoModel->obtenerPresentacionPorId($presentacionId);

    if (!$color || !$presentacion || (int) $color['id_producto'] !== (int) $producto['id'] || (int) $presentacion['id_producto'] !== (int) $producto['id']) {
        $_SESSION['reserva_mensaje'] = 'Encontramos una combinación que no pertenece a este producto.';
        header('Location: ' . BASE_URL . '/views/cliente/detalle_producto.php?id=' . $idProducto);
        exit;
    }

    $tipoPresentacion = $presentacion['tipo'] ?? 'metro';
    $precioUnitario = (float) ($presentacion['precio'] ?? 0);
    $metrosPorUnidad = (float) ($presentacion['metros_por_unidad'] ?? 0);

    if ($tipoPresentacion === 'rollo') {
        if (floor($cantidadSolicitada) != $cantidadSolicitada) {
            $_SESSION['reserva_mensaje'] = 'Los pedidos por rollo deben utilizar cantidades enteras.';
            header('Location: ' . BASE_URL . '/views/cliente/detalle_producto.php?id=' . $idProducto);
            exit;
        }
        if ($metrosPorUnidad <= 0) {
            $_SESSION['reserva_mensaje'] = 'La presentación por rollo carece de equivalencia en metros.';
            header('Location: ' . BASE_URL . '/views/cliente/detalle_producto.php?id=' . $idProducto);
            exit;
        }
    } else {
        if ($cantidadSolicitada < 0.5) {
            $_SESSION['reserva_mensaje'] = 'Cada pedido por metro debe ser al menos de 0.5 metros.';
            header('Location: ' . BASE_URL . '/views/cliente/detalle_producto.php?id=' . $idProducto);
            exit;
        }
        $multiplo = fmod($cantidadSolicitada * 2, 1.0);
        if (abs($multiplo) > 0.0001) {
            $_SESSION['reserva_mensaje'] = 'Trabajamos cortes en múltiplos de 0.5 metros para optimizar el tiempo de preparación.';
            header('Location: ' . BASE_URL . '/views/cliente/detalle_producto.php?id=' . $idProducto);
            exit;
        }
    }

    $stockVariante = $productoModel->obtenerStockVariante($idProducto, $colorId, $presentacionId);

    if ($stockVariante <= 0) {
        $_SESSION['reserva_mensaje'] = 'Una de las variantes seleccionadas está agotada.';
        header('Location: ' . BASE_URL . '/views/cliente/detalle_producto.php?id=' . $idProducto);
        exit;
    }

    if ($cantidadSolicitada > $stockVariante + 0.0001) {
        $_SESSION['reserva_mensaje'] = 'Solo hay ' . rtrim(rtrim(number_format($stockVariante, 2, '.', ''), '0'), '.') . ' unidades disponibles para una de las combinaciones elegidas.';
        header('Location: ' . BASE_URL . '/views/cliente/detalle_producto.php?id=' . $idProducto);
        exit;
    }

    $equivalenciaMetros = $tipoPresentacion === 'rollo'
        ? $cantidadSolicitada * max(1.0, $metrosPorUnidad)
        : $cantidadSolicitada;

    $subtotal = $precioUnitario * $cantidadSolicitada;

    $detallesConfirmados[] = [
        'color_id' => $colorId,
        'presentacion_id' => $presentacionId,
        'cantidad' => $cantidadSolicitada,
        'unidad' => $tipoPresentacion,
        'precio_unitario' => $precioUnitario,
        'subtotal' => $subtotal,
        'metros_equivalentes' => $equivalenciaMetros,
    ];

    $totalPedido += $subtotal;
    $metrosComprometidos += $equivalenciaMetros;
}

if (empty($detallesConfirmados)) {
    $_SESSION['reserva_mensaje'] = 'Agrega al menos una combinación válida para continuar con la reserva.';
    header('Location: ' . BASE_URL . '/views/cliente/detalle_producto.php?id=' . $idProducto);
    exit;
}

$stockDisponible = $inventarioModel->obtenerStockPorProducto($idProducto);

if ($stockDisponible <= 0) {
    $stockDisponible = $productoModel->obtenerStockEquivalente($idProducto);
}

if ($stockDisponible <= 0) {
    $_SESSION['reserva_mensaje'] = 'El producto no tiene stock disponible por el momento.';
    header('Location: ' . BASE_URL . '/views/cliente/detalle_producto.php?id=' . $idProducto);
    exit;
}

if ($metrosComprometidos > $stockDisponible + 0.0001) {
    $_SESSION['reserva_mensaje'] = 'Actualmente solo podemos comprometer ' . rtrim(rtrim(number_format($stockDisponible, 2, '.', ''), '0'), '.') . ' metros equivalentes para este producto.';
    header('Location: ' . BASE_URL . '/views/cliente/detalle_producto.php?id=' . $idProducto);
    exit;
}

try {
    global $pdo;
    $pdo->beginTransaction();

    $pedidoId = $pedidoModel->crearPedido($idUsuario, $idProducto, $totalPedido);

    if ($pedidoId <= 0) {
        throw new RuntimeException('No fue posible registrar el pedido.');
    }

    foreach ($detallesConfirmados as $detalle) {
        $detalleRegistrado = $pedidoModel->agregarDetalle(
            $pedidoId,
            $detalle['color_id'],
            $detalle['presentacion_id'],
            $detalle['cantidad'],
            $detalle['unidad'],
            $detalle['precio_unitario'],
            $detalle['subtotal']
        );

        if (!$detalleRegistrado) {
            throw new RuntimeException('No fue posible guardar una de las combinaciones seleccionadas.');
        }

        $varianteActualizada = $productoModel->disminuirStockVariante(
            $idProducto,
            $detalle['color_id'],
            $detalle['presentacion_id'],
            $detalle['cantidad']
        );

        if (!$varianteActualizada) {
            throw new RuntimeException('No fue posible actualizar el stock de una variación.');
        }
    }

    $inventarioActualizado = $inventarioModel->disminuirStock($idProducto, $metrosComprometidos);

    if ($inventarioActualizado) {
        $pdo->commit();
        $_SESSION['reserva_tipo'] = 'success';
        $_SESSION['reserva_mensaje'] = 'Reserva registrada correctamente. Puedes seguir su estado en la sección "Mis pedidos".';
    } else {
        $pdo->rollBack();
        $_SESSION['reserva_mensaje'] = 'No fue posible registrar tu reserva. Inténtalo de nuevo en unos minutos.';
    }
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Error al registrar reserva: ' . $e->getMessage());
    $_SESSION['reserva_mensaje'] = 'Hubo un inconveniente al guardar tu reserva. Por favor intenta más tarde.';
}

header('Location: ' . BASE_URL . '/views/cliente/pedidos.php');
exit;
