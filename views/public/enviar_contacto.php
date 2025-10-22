<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $mensaje = $_POST['mensaje'] ?? '';

    if ($nombre && $email && $mensaje) {
        // Conexión a la base de datos
        require_once __DIR__ . '/../config/database.php';

        // Insertar comentario en la base de datos
        try {
            $stmt = $pdo->prepare("INSERT INTO comentarios (id_producto, id_usuario, comentario) VALUES (:id_producto, :id_usuario, :comentario)");
            $stmt->execute([
                'id_producto' => 1, // Producto 1 o el que sea
                'id_usuario' => 1,  // Usuario que manda el mensaje
                'comentario' => $mensaje
            ]);

            // Redirigir con mensaje de éxito
            header('Location: ' . BASE_URL . '/views/public/contacto.php?status=success');
        } catch (PDOException $e) {
            error_log('Error al guardar el mensaje: ' . $e->getMessage());
            header('Location: ' . BASE_URL . '/views/public/contacto.php?status=error');
        }
    } else {
        header('Location: ' . BASE_URL . '/views/public/contacto.php?status=error');
    }
}
?>
