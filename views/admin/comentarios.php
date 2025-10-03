<!-- comentarios.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="container mt-4">
    <h2 class="text-center">Revisión de Comentarios</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cliente</th>
                <th>Comentario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($comentarios as $comentario): ?>
            <tr>
                <td><?= $comentario['producto'] ?></td>
                <td><?= $comentario['usuario'] ?></td>
                <td><?= $comentario['comentario'] ?></td>
                <td>
                    <a href="eliminar_comentario.php?id=<?= $comentario['id'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
