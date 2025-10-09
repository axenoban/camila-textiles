<?php
require_once __DIR__ . '/../../models/comentario.php';

$comentarioModel = new Comentario();
$comentarios = $comentarioModel->obtenerComentarios();
?>
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
                        <?php if (!empty($comentarios)): ?>
                            <?php foreach ($comentarios as $comentario): ?>
                            <tr>
                                <td><?= htmlspecialchars($comentario['producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($comentario['usuario'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($comentario['comentario'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-end text-nowrap">
                                    <a href="eliminar_comentario.php?id=<?= (int) $comentario['id']; ?>" class="btn btn-danger btn-sm disabled" aria-disabled="true">Eliminar</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No hay comentarios registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
