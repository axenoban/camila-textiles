<!-- views/cliente/dashboard.php -->
<?php include('../includes/header.php'); ?>
<?php include('../includes/navbar.php'); ?>

<div class="container mt-4">
    <h2 class="text-center">Bienvenido al Panel del Cliente</h2>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Historial de Pedidos</h5>
                    <p class="card-text">Consulta el estado de tus pedidos anteriores.</p>
                    <a href="pedidos.php" class="btn btn-primary">Ver Historial</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Productos Disponibles</h5>
                    <p class="card-text">Explora todos los productos disponibles en nuestra tienda.</p>
                    <a href="productos.php" class="btn btn-success">Ver Productos</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tu Perfil</h5>
                    <p class="card-text">Actualiza tu información personal y preferencias.</p>
                    <a href="perfil.php" class="btn btn-info">Ver Perfil</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
