<!-- views/admin/agregar_sucursal.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <header class="page-header text-center text-lg-start">
            <h1 class="page-title">Registrar nueva sucursal</h1>
            <p class="page-subtitle">Integra puntos de venta al ecosistema digital para sincronizar stock y pedidos.</p>
        </header>
        <div class="row justify-content-center">
            <div class="col-12 col-xl-7">
                <div class="form-shell">
                    <form action="sucursales.php" method="POST" class="row g-4">
                        <div class="col-12">
                            <label for="nombre" class="form-label">Nombre de la sucursal</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-12">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                        </div>
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="col-md-6">
                            <label for="horario_apertura" class="form-label">Horario de atención</label>
                            <input type="text" class="form-control" id="horario_apertura" name="horario_apertura" required>
                        </div>
                        <div class="col-12 text-end">
                            <a href="sucursales.php" class="btn btn-outline-primary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-success">Guardar sucursal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
