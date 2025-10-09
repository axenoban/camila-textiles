<!-- views/admin/agregar_empleado.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <header class="page-header text-center text-lg-start">
            <h1 class="page-title">Nuevo integrante del equipo</h1>
            <p class="page-subtitle">Registra a colaboradores con su puesto y salario para habilitar accesos al sistema.</p>
        </header>
        <div class="row justify-content-center">
            <div class="col-12 col-xl-7">
                <div class="form-shell">
                    <form action="<?= BASE_URL ?>/controllers/empleados.php" method="POST" class="row g-4">
                        <input type="hidden" name="accion" value="crear">
                        <div class="col-12">
                            <label for="nombre" class="form-label">Nombre del empleado</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="puesto" class="form-label">Puesto</label>
                            <input type="text" class="form-control" id="puesto" name="puesto" required>
                        </div>
                        <div class="col-md-6">
                            <label for="salario" class="form-label">Salario mensual (USD)</label>
                            <input type="number" class="form-control" id="salario" name="salario" step="0.01" required>
                        </div>
                        <div class="col-12 text-end">
                            <a href="empleados.php" class="btn btn-outline-primary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-success">Guardar empleado</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
