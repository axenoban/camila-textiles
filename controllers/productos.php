<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/producto.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario || ($usuario['rol'] ?? null) !== 'administrador') {
    header('Location: ' . BASE_URL . '/views/public/login.php');
    exit;
}

$productoModel = new Producto();
$accion = $_POST['accion'] ?? $_GET['accion'] ?? null;

function redirigirProductos(array $params = []): void
{
    $url = BASE_URL . '/views/admin/productos.php';
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    header('Location: ' . $url);
    exit;
}

try {
    switch ($accion) {
        case 'crear':
            // Obtener datos del formulario
            $data = [
                'nombre'           => trim($_POST['nombre'] ?? ''),
                'descripcion'      => trim($_POST['descripcion'] ?? ''),
                'ancho_metros'     => (float)($_POST['ancho_metros'] ?? 1.60),
                'composicion'      => trim($_POST['composicion'] ?? ''),
                'tipo_tela'        => trim($_POST['tipo_tela'] ?? ''),
                'elasticidad'      => trim($_POST['elasticidad'] ?? ''),
                'precio_metro'     => (float)($_POST['precio_metro'] ?? 0),
                'precio_rollo'     => (float)($_POST['precio_rollo'] ?? 0),
                'metros_por_rollo' => (float)($_POST['metros_por_rollo'] ?? 25),
                'imagen_principal' => '',
                'visible'          => isset($_POST['visible']) ? 1 : 0
            ];

            // Validaciones
            if (
                $data['nombre'] === '' ||
                $data['descripcion'] === '' ||
                $data['precio_metro'] <= 0 ||
                $data['precio_rollo'] <= 0
            ) {
                redirigirProductos(['status' => 'error']);
            }

            // Manejo de la imagen
            if (isset($_FILES['imagen_local']) && $_FILES['imagen_local']['error'] === UPLOAD_ERR_OK) {
                // Validar tipo de imagen
                $fileTmpPath = $_FILES['imagen_local']['tmp_name'];
                $fileName = $_FILES['imagen_local']['name'];
                $fileSize = $_FILES['imagen_local']['size'];
                $fileType = $_FILES['imagen_local']['type'];

                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                $uploadDir = __DIR__ . '/../uploads/';

                // Verificar si el tipo de archivo es válido
                if (!in_array($fileType, $allowedTypes)) {
                    redirigirProductos(['status' => 'error']);
                }

                // Generar un nombre único para la imagen
                $newFileName = uniqid() . '_' . $fileName;
                $destPath = $uploadDir . $newFileName;

                // Mover el archivo al directorio de destino
                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $data['imagen_principal'] = $destPath; // Usar la ruta local
                } else {
                    redirigirProductos(['status' => 'error']);
                }
            } elseif (isset($_POST['imagen']) && $_POST['imagen'] !== '') {
                // Si no se sube una imagen local, se guarda la URL proporcionada
                $data['imagen_principal'] = trim($_POST['imagen']);
            }

            // Preparar la consulta SQL
            $sql = "INSERT INTO productos (nombre, descripcion, ancho_metros, composicion, tipo_tela, elasticidad, precio_metro, precio_rollo, metros_por_rollo, imagen_principal, visible) 
                    VALUES (:nombre, :descripcion, :ancho_metros, :composicion, :tipo_tela, :elasticidad, :precio_metro, :precio_rollo, :metros_por_rollo, :imagen_principal, :visible)";

            // Preparar la consulta
            $stmt = $pdo->prepare($sql);

            // Ejecutar la consulta
            $resultado = $stmt->execute([
                'nombre'           => $data['nombre'],
                'descripcion'      => $data['descripcion'],
                'ancho_metros'     => $data['ancho_metros'],
                'composicion'      => $data['composicion'],
                'tipo_tela'        => $data['tipo_tela'],
                'elasticidad'      => $data['elasticidad'],
                'precio_metro'     => $data['precio_metro'],
                'precio_rollo'     => $data['precio_rollo'],
                'metros_por_rollo' => $data['metros_por_rollo'],
                'imagen_principal' => $data['imagen_principal'],
                'visible'          => $data['visible']
            ]);

            if ($resultado) {
                redirigirProductos(['status' => 'creado']);
            } else {
                redirigirProductos(['status' => 'error']);
            }
            break;

        case 'actualizar':
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if (!$id) {
                header('Location: ' . BASE_URL . '/views/admin/productos.php?status=error');
                exit;
            }

            $data = [
                'nombre'           => trim($_POST['nombre'] ?? ''),
                'descripcion'      => trim($_POST['descripcion'] ?? ''),
                'ancho_metros'     => (float)($_POST['ancho_metros'] ?? 1.60),
                'composicion'      => trim($_POST['composicion'] ?? ''),
                'tipo_tela'        => trim($_POST['tipo_tela'] ?? ''),
                'elasticidad'      => trim($_POST['elasticidad'] ?? ''),
                'precio_metro'     => (float)($_POST['precio_metro'] ?? 0),
                'precio_rollo'     => (float)($_POST['precio_rollo'] ?? 0),
                'metros_por_rollo' => (float)($_POST['metros_por_rollo'] ?? 25),
                'imagen_principal' => trim($_POST['imagen_principal'] ?? ''), // Mantener imagen si no se sube una nueva
                'visible'          => isset($_POST['visible']) ? 1 : 0
            ];

            // Verificar si se subió una nueva imagen
            if (isset($_FILES['imagen_local']) && $_FILES['imagen_local']['error'] === UPLOAD_ERR_OK) {
                // Eliminar la imagen anterior si existe
                if (!empty($producto['imagen_principal']) && file_exists(__DIR__ . '/../uploads/' . basename($producto['imagen_principal']))) {
                    unlink(__DIR__ . '/../uploads/' . basename($producto['imagen_principal']));
                }

                // Subir nueva imagen
                $fileTmpPath = $_FILES['imagen_local']['tmp_name'];
                $fileName = $_FILES['imagen_local']['name'];
                $fileType = $_FILES['imagen_local']['type'];
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                $uploadDir = __DIR__ . '/../uploads/';

                if (!in_array($fileType, $allowedTypes)) {
                    header('Location: ' . BASE_URL . '/views/admin/productos.php?status=error');
                    exit;
                }

                $newFileName = uniqid() . '_' . $fileName;
                $destPath = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $data['imagen_principal'] = $destPath; // Usar la ruta local
                } else {
                    // Si el archivo no se movió correctamente, mostrar un error SQL
                    echo "Error al subir el archivo. Verifica los permisos de la carpeta uploads.";
                    exit;
                }
            }

            // Preparar la consulta de actualización
            $sql = "UPDATE productos SET 
    nombre = :nombre,
    descripcion = :descripcion,
    ancho_metros = :ancho_metros,
    composicion = :composicion,
    tipo_tela = :tipo_tela,
    elasticidad = :elasticidad,
    precio_metro = :precio_metro,
    precio_rollo = :precio_rollo,
    metros_por_rollo = :metros_por_rollo,
    imagen_principal = :imagen_principal,
    visible = :visible
WHERE id = :id";

            $stmt = $pdo->prepare($sql);

            // Ejecutar la consulta
            if ($stmt->execute([
                'id'               => $id,
                'nombre'           => $data['nombre'],
                'descripcion'      => $data['descripcion'],
                'ancho_metros'     => $data['ancho_metros'],
                'composicion'      => $data['composicion'],
                'tipo_tela'        => $data['tipo_tela'],
                'elasticidad'      => $data['elasticidad'],
                'precio_metro'     => $data['precio_metro'],
                'precio_rollo'     => $data['precio_rollo'],
                'metros_por_rollo' => $data['metros_por_rollo'],
                'imagen_principal' => $data['imagen_principal'],
                'visible'          => $data['visible']
            ])) {
                // Si la actualización es exitosa
                header('Location: ' . BASE_URL . '/views/admin/productos.php?status=actualizado');
                exit;
            } else {
                // Si hubo un error en la ejecución de la consulta SQL, mostrar el error
                echo "Error en la consulta SQL: " . $stmt->errorInfo()[2];
                exit;
            }
            break;

        case 'eliminar':
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT); // Cambiar a POST
            if ($id) {
                // Llamar al método eliminarProducto
                $productoModel = new Producto();
                $resultado = $productoModel->eliminarProducto($id);

                if ($resultado) {
                    echo json_encode(['status' => 'success', 'message' => 'Producto eliminado correctamente o desactivado']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar o desactivar el producto']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ID de producto no válido']);
            }
            exit;






            redirigirProductos();
    }
} catch (Throwable $e) {
    error_log('Error en gestión de productos: ' . $e->getMessage());
    redirigirProductos(['status' => 'error']);
}
