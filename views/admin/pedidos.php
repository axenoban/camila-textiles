<!-- views/admin/pedidos.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <header class="page-header text-center text-lg-start">
            <h1 class="page-title">Gestión de pedidos</h1>
            <p class="page-subtitle">Sigue el estado de cada solicitud y coordina entregas o reservas con un solo clic.</p>
        </header>
        <div class="table-shell">
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Cliente</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td><?= $pedido['id'] ?></td>
                                <td><?= $pedido['cliente'] ?></td>
                                <td><?= $pedido['producto'] ?></td>
                                <td><?= $pedido['cantidad'] ?></td>
                                <td><?= $pedido['estado'] ?></td>
                                <td class="text-end text-nowrap">
                                    <a href="confirmar_pedido.php?id=<?= $pedido['id'] ?>" class="btn btn-success btn-sm me-2">Confirmar</a>
                                    <a href="cancelar_pedido.php?id=<?= $pedido['id'] ?>" class="btn btn-danger btn-sm">Cancelar</a>
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
