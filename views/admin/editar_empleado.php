<!-- views/admin/editar_empleado.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <header class="page-header text-center text-lg-start">
            <h1 class="page-title">Actualizar datos del empleado</h1>
            <p class="page-subtitle">Mantén la información de tu equipo al día para una gestión interna precisa.</p>
        </header>
        <div class="row justify-content-center">
            <div class="col-12 col-xl-7">
                <div class="form-shell">
                    <form action="empleados.php" method="POST" class="row g-4">
                        <input type="hidden" name="id" value="<?= $empleado['id'] ?>">
                        <div class="col-12">
                            <label for="nombre" class="form-label">Nombre del empleado</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $empleado['nombre'] ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="puesto" class="form-label">Puesto</label>
                            <input type="text" class="form-control" id="puesto" name="puesto" value="<?= $empleado['puesto'] ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="salario" class="form-label">Salario mensual (USD)</label>
                            <input type="number" class="form-control" id="salario" name="salario" step="0.01" value="<?= $empleado['salario'] ?>" required>
                        </div>
                        <div class="col-12 text-end">
                            <a href="empleados.php" class="btn btn-outline-primary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-warning">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
