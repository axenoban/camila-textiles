<!-- views/admin/comentarios.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <header class="page-header text-center text-lg-start">
            <h1 class="page-title">Comentarios de clientes</h1>
            <p class="page-subtitle">Analiza la retroalimentación recibida y modera las reseñas asociadas a cada producto.</p>
        </header>
        <div class="table-shell">
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cliente</th>
                            <th>Comentario</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comentarios as $comentario): ?>
                            <tr>
                                <td><?= $comentario['producto'] ?></td>
                                <td><?= $comentario['usuario'] ?></td>
                                <td><?= $comentario['comentario'] ?></td>
                                <td class="text-end text-nowrap">
                                    <a href="eliminar_comentario.php?id=<?= $comentario['id'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
