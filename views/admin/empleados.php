<?php
require_once __DIR__ . '/../../models/empleado.php';

$empleadoModel = new Empleado();
$empleados = $empleadoModel->obtenerEmpleados();
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
                                    <a href="<?= BASE_URL ?>/controllers/empleados.php?accion=eliminar&amp;id=<?= (int) $empleado['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
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
