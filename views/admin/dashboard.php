<!-- views/admin/dashboard.php -->
<?php include('../includes/header.php'); ?>
<?php include('../includes/navbar.php'); ?>

<div class="container mt-4">
    <h2 class="text-center">Panel de Administración</h2>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total de Productos</h5>
                    <p class="card-text">Visualiza todos los productos disponibles en la tienda.</p>
                    <a href="productos.php" class="btn btn-primary">Ver Productos</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pedidos Pendientes</h5>
                    <p class="card-text">Gestiona los pedidos pendientes de los clientes.</p>
                    <a href="pedidos.php" class="btn btn-warning">Ver Pedidos</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Empleados</h5>
                    <p class="card-text">Gestiona la información de los empleados.</p>
                    <a href="empleados.php" class="btn btn-info">Ver Empleados</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>

