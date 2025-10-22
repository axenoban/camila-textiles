<?php
require_once __DIR__ . '/../database/conexion.php'; // Conexión a la base de datos

class Cliente {
    // Obtener todos los clientes
    public function obtenerClientes() {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM usuarios");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cambiarEstadoCliente($id, $estado) {
    global $pdo;

    // Verificamos que el estado sea válido (habilitado o bloqueado)
    if (!in_array($estado, ['habilitado', 'bloqueado'])) {
        return false; // Si el estado no es válido, no hacer nada
    }

    $stmt = $pdo->prepare("UPDATE usuarios SET estado = :estado WHERE id = :id");
    return $stmt->execute(['id' => $id, 'estado' => $estado]);
}

    // Eliminar un cliente
    public function eliminarCliente($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Obtener número de pedidos y ventas totales de un cliente
    public function obtenerPedidosYVentas($idUsuario) {
        global $pdo;

        // Contar los pedidos realizados por el cliente
        $stmt = $pdo->prepare("SELECT COUNT(*) AS total_pedidos, SUM(total) AS total_ventas 
                               FROM pedidos WHERE id_usuario = :idUsuario AND estado = 'completado'");
        $stmt->execute(['idUsuario' => $idUsuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
