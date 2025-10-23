<?php
require_once __DIR__ . '/../../models/empleado.php';

$empleadoModel = new Empleado();
$empleados = $empleadoModel->obtenerEmpleados();

// Lógica para eliminar un empleado
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        $eliminado = $empleadoModel->eliminarEmpleado($id);
        if ($eliminado) {
            header('Location: empleados.php?status=eliminado');
            exit;
        } else {
            header('Location: empleados.php?status=error');
            exit;
        }
    }
}
?>
<!-- views/admin/empleados.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <?php
        $status = $_GET['status'] ?? null;
        $mensajes = [
            'creado' => ['type' => 'success', 'text' => 'El empleado se registró correctamente.'],
            'actualizado' => ['type' => 'success', 'text' => 'Los datos del empleado se actualizaron.'],
            'eliminado' => ['type' => 'success', 'text' => 'El empleado se eliminó del sistema.'],
            'no_encontrado' => ['type' => 'warning', 'text' => 'El empleado solicitado no existe.'],
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
                    <h1 class="page-title mb-2">Equipo de Camila Textil</h1>
                    <p class="page-subtitle mb-0">Gestiona colaboradores, asigna roles y controla el acceso al sistema.</p>
                </div>
                <a href="agregar_empleado.php" class="btn btn-success">Nuevo empleado</a>
            </div>
        </header>
        <div class="table-shell">
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Puesto</th>
                            <th>Salario</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($empleados)): ?>
                            <?php foreach ($empleados as $empleado): ?>
                            <tr>
                                <td><?= (int) $empleado['id']; ?></td>
                                <td><?= htmlspecialchars($empleado['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($empleado['puesto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= '$' . number_format((float) $empleado['salario'], 2); ?></td>
                                <td class="text-end text-nowrap">
                                    <a href="editar_empleado.php?id=<?= (int) $empleado['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                    <!-- Enlace para eliminar empleado -->
                                    <a href="empleados.php?accion=eliminar&id=<?= (int) $empleado['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este empleado?');">Eliminar</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No se han registrado empleados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
