<!-- reportes.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="container mt-4">
    <h2 class="text-center">Generación de Reportes</h2>
    <p class="text-center">Genera informes de ventas, pedidos, y más.</p>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Reporte de Ventas</h5>
                    <p class="card-text">Genera un reporte de todas las ventas realizadas.</p>
                    <a href="#" class="btn btn-primary">Generar Reporte</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Reporte de Pedidos</h5>
                    <p class="card-text">Genera un reporte de los pedidos realizados por los clientes.</p>
                    <a href="#" class="btn btn-primary">Generar Reporte</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Reporte de Inventario</h5>
                    <p class="card-text">Genera un reporte del estado actual del inventario.</p>
                    <a href="#" class="btn btn-primary">Generar Reporte</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
