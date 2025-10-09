<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../models/sucursal.php';

$sucursalModel = new Sucursal();
$sucursalId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$sucursalId) {
    header('Location: ' . BASE_URL . '/views/admin/sucursales.php');
    exit;
}

$sucursal = $sucursalModel->obtenerSucursalPorId($sucursalId);

if (!$sucursal) {
    header('Location: ' . BASE_URL . '/views/admin/sucursales.php?status=no_encontrado');
    exit;
}
?>
<!-- views/admin/editar_sucursal.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <header class="page-header text-center text-lg-start">
            <h1 class="page-title">Editar sucursal</h1>
            <p class="page-subtitle">Actualiza la información de tu punto de venta para mantener datos confiables en todo el equipo.</p>
        </header>
        <div class="row justify-content-center">
            <div class="col-12 col-xl-7">
                <div class="form-shell">
                    <form action="<?= BASE_URL ?>/controllers/sucursales.php" method="POST" class="row g-4">
                        <input type="hidden" name="accion" value="actualizar">
                        <input type="hidden" name="id" value="<?= (int) $sucursal['id']; ?>">
                        <div class="col-12">
                            <label for="nombre" class="form-label">Nombre de la sucursal</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($sucursal['nombre'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="col-12">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" value="<?= htmlspecialchars($sucursal['direccion'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="<?= htmlspecialchars($sucursal['telefono'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="horario_apertura" class="form-label">Horario de atención</label>
                            <input type="text" class="form-control" id="horario_apertura" name="horario_apertura" value="<?= htmlspecialchars($sucursal['horario_apertura'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="col-12 text-end">
                            <a href="sucursales.php" class="btn btn-outline-primary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-warning">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
