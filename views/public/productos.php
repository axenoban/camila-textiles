<!-- views/public/productos.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="container mt-4">
    <h2 class="text-center">Catálogo de Productos</h2>
    <div class="row mt-4">
        <?php foreach ($productos as $producto): ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="<?= $producto['imagen'] ?>" class="card-img-top" alt="<?= $producto['nombre'] ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= $producto['nombre'] ?></h5>
                    <p class="card-text"><?= $producto['descripcion'] ?></p>
                    <p class="card-text"><strong>$<?= number_format($producto['precio'], 2) ?></strong></p>
                    <a href="/camila-textil/views/public/productos.php" class="btn btn-primary">Ver Detalles</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>
