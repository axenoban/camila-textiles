<?php
require_once __DIR__ . '/../../models/inventario.php';

$inventarioModel = new Inventario();
$inventario = $inventarioModel->obtenerInventario();
?>
<!-- views/admin/inventario.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <header class="page-header text-center text-lg-start">
            <h1 class="page-title">Control de inventario</h1>
            <p class="page-subtitle">Visualiza existencias en tiempo real y ejecuta ajustes o reposiciones para mantener el flujo de ventas.</p>
        </header>
        <div class="table-shell">
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad disponible</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($inventario)): ?>
                            <?php foreach ($inventario as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= (int) $item['cantidad']; ?></td>
                                <td class="text-end text-nowrap">
                                    <a href="agregar_inventario.php?id=<?= (int) $item['id_producto']; ?>" class="btn btn-info btn-sm me-2">Ajustar stock</a>
                                    <a href="eliminar_inventario.php?id=<?= (int) $item['id_producto']; ?>" class="btn btn-danger btn-sm">Reducir</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No hay existencias registradas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
