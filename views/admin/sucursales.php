<?php
require_once __DIR__ . '/../../models/sucursal.php'; // Ruta corregida

$sucursalModel = new Sucursal();
$sucursales = $sucursalModel->obtenerSucursales();
?>
<!-- views/admin/sucursales.php -->
<?php include(__DIR__ . '/includes/header.php'); ?>
<?php include(__DIR__ . '/includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <?php
        // Manejo de mensajes de estado
        $status = $_GET['status'] ?? null;
        $mensajes = [
            'creado' => ['type' => 'success', 'text' => 'La sucursal se registró exitosamente.'],
            'actualizado' => ['type' => 'success', 'text' => 'La información de la sucursal se actualizó.'],
            'eliminado' => ['type' => 'success', 'text' => 'La sucursal se eliminó.'],
            'no_encontrado' => ['type' => 'warning', 'text' => 'La sucursal indicada no existe.'],
            'error' => ['type' => 'danger', 'text' => 'No se pudo completar la operación.'],
            'visibilidad' => ['type' => 'info', 'text' => 'Se actualizó la visibilidad de la sucursal.']
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
                    <h1 class="page-title mb-2">Mapa de sucursales</h1>
                    <p class="page-subtitle mb-0 text-muted">Coordina la operación de cada punto de venta y mantén horarios y contactos siempre actualizados.</p>
                </div>
                <a href="agregar_sucursal.php" class="btn btn-success">Nueva sucursal</a>
            </div>
        </header>

        <div class="table-shell">
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Horario</th>
                            <th>Visibilidad</th>
                            <th>Ubicación</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($sucursales)): ?>
                            <?php foreach ($sucursales as $sucursal): ?>
                                <tr>
                                    <td><?= (int) $sucursal['id']; ?></td>
                                    <td><?= htmlspecialchars($sucursal['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($sucursal['direccion'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($sucursal['telefono'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($sucursal['horario_apertura'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <?php if ($sucursal['visible']): ?>
                                            <span class="badge bg-success">Visible</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Oculto</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <!-- Enlace al mapa de Google con las coordenadas de latitud y longitud -->
                                        <a href="https://www.google.com/maps?q=<?= $sucursal['latitud'] ?>,<?= $sucursal['longitud'] ?>" target="_blank" class="btn btn-info btn-sm">
                                            Ver en Mapa
                                        </a>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <!-- Editar sucursal -->
                                        <a href="editar_sucursal.php?id=<?= (int)$sucursal['id']; ?>" class="btn btn-warning btn-sm me-2">Editar</a>

                                        <!-- Cambiar visibilidad -->
                                        <a href="toggle_visibilidad.php?id=<?= (int)$sucursal['id']; ?>" class="btn btn-info btn-sm me-2">
                                            <?= $sucursal['visible'] ? 'Ocultar' : 'Mostrar' ?>
                                        </a>

                                        <!-- Eliminar sucursal -->
                                        <a href="eliminar_sucursal.php?id=<?= (int)$sucursal['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No se han cargado sucursales.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include(__DIR__ . '/includes/footer.php'); ?>
