<?php
require_once __DIR__ . '/../../database/conexion.php'; // Conexión a la base de datos

// Comienza la sesión para acceder a los datos del usuario
session_start();

if (empty($_SESSION['usuario']) || ($_SESSION['usuario']['rol'] ?? '') !== 'administrador') {
    header('Location: ' . BASE_URL . '/views/public/login.php');
    exit;
}

// Definimos la clase Cliente dentro de la misma página
class Cliente {
    // Método para obtener todos los clientes
    public function obtenerClientes() {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM usuarios");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para cambiar el estado de un cliente (habilitar o bloquear)
    public function cambiarEstadoCliente($id, $estado) {
        global $pdo;

        if (!in_array($estado, ['habilitado', 'bloqueado'])) {
            return false; // Si el estado no es válido, no hacer nada
        }

        $stmt = $pdo->prepare("UPDATE usuarios SET estado = :estado WHERE id = :id");
        return $stmt->execute(['id' => $id, 'estado' => $estado]);
    }

    // Método para eliminar un cliente
    public function eliminarCliente($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Método para agregar un nuevo cliente
    public function agregarCliente($nombre, $email, $clave, $rol) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, clave, rol, estado) VALUES (:nombre, :email, :clave, :rol, 'habilitado')");
        return $stmt->execute(['nombre' => $nombre, 'email' => $email, 'clave' => password_hash($clave, PASSWORD_BCRYPT), 'rol' => $rol]);
    }
}

// Instanciamos el modelo Cliente
$clienteModel = new Cliente();

// Lógica para manejar las acciones (cambiar estado, eliminar, agregar cliente)
$accion = $_GET['accion'] ?? null;

if ($accion) {
    try {
        switch ($accion) {
            case 'cambiarEstado':
                $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                $estado = $_GET['estado'] ?? 'habilitado'; // Estado por defecto 'habilitado'
                if ($id) {
                    $clienteModel->cambiarEstadoCliente($id, $estado);
                    header('Location: clientes.php?status=' . ($estado === 'habilitado' ? 'habilitado' : 'bloqueado'));
                    exit;
                }
                header('Location: clientes.php?status=error');
                break;

            case 'eliminar':
                $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                if ($id) {
                    $clienteModel->eliminarCliente($id);
                    header('Location: clientes.php?status=eliminado');
                    exit;
                }
                header('Location: clientes.php?status=error');
                break;

            case 'agregar':
                // Verifica los datos del formulario de agregar cliente
                $nombre = $_POST['nombre'] ?? '';
                $email = $_POST['email'] ?? '';
                $clave = $_POST['clave'] ?? '';
                $rol = $_POST['rol'] ?? 'cliente';
                if ($nombre && $email && $clave) {
                    $clienteModel->agregarCliente($nombre, $email, $clave, $rol);
                    header('Location: clientes.php?status=creado');
                    exit;
                }
                header('Location: clientes.php?status=error');
                break;

            default:
                header('Location: clientes.php');
                exit;
        }
    } catch (Exception $e) {
        error_log('Error al procesar la solicitud: ' . $e->getMessage());
        header('Location: clientes.php?status=error');
    }
}

// Obtener los clientes
$clientes = $clienteModel->obtenerClientes();
?>

<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <?php
        $status = $_GET['status'] ?? null;
        $mensajes = [
            'creado' => ['type' => 'success', 'text' => 'El usuario se registró correctamente.'],
            'actualizado' => ['type' => 'success', 'text' => 'Los datos del usuario se actualizaron.'],
            'eliminado' => ['type' => 'success', 'text' => 'El usuario se eliminó del sistema.'],
            'habilitado' => ['type' => 'success', 'text' => 'El usuario se habilitó.'],
            'bloqueado' => ['type' => 'warning', 'text' => 'El usuario se bloqueó.'],
            'error' => ['type' => 'danger', 'text' => 'No se pudo completar la operación. Inténtalo nuevamente.'],
        ];

        if ($status && isset($mensajes[$status])): ?>
        <div class="alert alert-<?= $mensajes[$status]['type']; ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($mensajes[$status]['text'], ENT_QUOTES, 'UTF-8'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
        <?php endif; ?>

        <header class="page-header text-center text-lg-start">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h1 class="page-title mb-2">Usuarios de Camila Textil</h1>
                    <p class="page-subtitle mb-0">Gestiona usuarios, asigna roles y controla el acceso al sistema.</p>
                </div>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregarUsuarioModal">Nuevo Usuario</button>
            </div>
        </header>

        <!-- Modal para agregar usuario -->
        <div class="modal fade" id="agregarUsuarioModal" tabindex="-1" aria-labelledby="agregarUsuarioModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="agregarUsuarioModalLabel">Agregar Nuevo Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form action="clientes.php?accion=agregar" method="POST">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="clave" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="clave" name="clave" required>
                            </div>
                            <div class="mb-3">
                                <label for="rol" class="form-label">Rol</label>
                                <select class="form-control" id="rol" name="rol">
                                    <option value="cliente">Cliente</option>
                                    <option value="administrador">Administrador</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Agregar Usuario</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-shell">
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($clientes)): ?>
                            <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?= (int) $cliente['id']; ?></td>
                                <td><?= htmlspecialchars($cliente['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($cliente['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($cliente['rol'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($cliente['estado'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-end text-nowrap">
                                    <?php if ($cliente['estado'] == 'habilitado'): ?>
                                        <a href="clientes.php?accion=cambiarEstado&id=<?= (int) $cliente['id']; ?>&estado=bloqueado" class="btn btn-warning btn-sm">Bloquear</a>
                                    <?php else: ?>
                                        <a href="clientes.php?accion=cambiarEstado&id=<?= (int) $cliente['id']; ?>&estado=habilitado" class="btn btn-success btn-sm">Desbloquear</a>
                                    <?php endif; ?>
                                    <a href="clientes.php?accion=eliminar&id=<?= (int) $cliente['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Eliminar</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No se han registrado usuarios.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
