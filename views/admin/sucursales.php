<!-- views/admin/sucursales.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <header class="page-header text-center text-lg-start">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h1 class="page-title mb-2">Mapa de sucursales</h1>
                    <p class="page-subtitle mb-0">Coordina la operación de cada punto de venta y mantén horarios y contactos siempre actualizados.</p>
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
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sucursales as $sucursal): ?>
                            <tr>
                                <td><?= $sucursal['id'] ?></td>
                                <td><?= $sucursal['nombre'] ?></td>
                                <td><?= $sucursal['direccion'] ?></td>
                                <td><?= $sucursal['telefono'] ?></td>
                                <td><?= $sucursal['horario_apertura'] ?></td>
                                <td class="text-end text-nowrap">
                                    <a href="editar_sucursal.php?id=<?= $sucursal['id'] ?>" class="btn btn-warning btn-sm me-2">Editar</a>
                                    <a href="eliminar_sucursal.php?id=<?= $sucursal['id'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
