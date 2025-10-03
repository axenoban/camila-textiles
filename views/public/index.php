<!-- views/public/index.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center">Bienvenido a Camila Textil</h1>
            <p class="text-center">Tu tienda de telas premium importadas desde China. ¡Descubre la mejor calidad en telas para tus proyectos!</p>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <h2 class="text-center">Productos Destacados</h2>
            <div class="row">
                <?php foreach ($productos as $producto): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?= $producto['imagen'] ?>" class="card-img-top" alt="<?= $producto['nombre'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $producto['nombre'] ?></h5>
                            <p class="card-text"><?= $producto['descripcion'] ?></p>
                            <p class="card-text"><strong>$<?= number_format($producto['precio'], 2) ?></strong></p>
                            <a href="/camila-textil/views/public/productos.php" class="btn btn-primary">Ver Productos</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
