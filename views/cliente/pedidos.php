<!-- views/cliente/pedidos.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-area">
    <div class="container-fluid px-4 px-lg-5">
        <section class="client-section text-center text-lg-start">
            <h1 class="section-heading">Mis pedidos</h1>
            <p class="section-subtitle">Supervisa tus pedidos activos y consulta tu historial en un panel claro y accesible.</p>
        </section>
        <div class="client-section">
            <h2 class="h5 fw-semibold mb-3">Pedidos actuales</h2>
            <div class="portal-table">
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID Pedido</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Unidad</th>
                                <th>Estado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pedidosActivos as $pedido): ?>
                                <?php
                                    $producto = htmlspecialchars($pedido['producto'] ?? '', ENT_QUOTES, 'UTF-8');
                                    $estado = htmlspecialchars($pedido['estado'] ?? '', ENT_QUOTES, 'UTF-8');
                                    $unidad = isset($pedido['unidad_venta']) && $pedido['unidad_venta'] === 'rollo' ? 'Rollo' : 'Metro';
                                ?>
                                <tr>
                                    <td><?= $pedido['id'] ?></td>
                                    <td><?= $producto ?></td>
                                    <td><?= number_format($pedido['cantidad'], 2, ',', '.') ?></td>
                                    <td><?= $unidad ?></td>
                                    <td><?= $estado ?></td>
                                    <td class="text-end text-nowrap">
                                        <a href="ver_detalles_pedido.php?id=<?= $pedido['id'] ?>" class="btn btn-info btn-sm">Ver detalles</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="client-section">
            <h2 class="h5 fw-semibold mb-3">Historial de pedidos</h2>
            <div class="portal-table">
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID Pedido</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Unidad</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pedidosHistoricos as $pedido): ?>
                                <?php
                                    $producto = htmlspecialchars($pedido['producto'] ?? '', ENT_QUOTES, 'UTF-8');
                                    $estado = htmlspecialchars($pedido['estado'] ?? '', ENT_QUOTES, 'UTF-8');
                                    $fecha = htmlspecialchars($pedido['fecha'] ?? '', ENT_QUOTES, 'UTF-8');
                                    $unidad = isset($pedido['unidad_venta']) && $pedido['unidad_venta'] === 'rollo' ? 'Rollo' : 'Metro';
                                ?>
                                <tr>
                                    <td><?= $pedido['id'] ?></td>
                                    <td><?= $producto ?></td>
                                    <td><?= number_format($pedido['cantidad'], 2, ',', '.') ?></td>
                                    <td><?= $unidad ?></td>
                                    <td><?= $estado ?></td>
                                    <td><?= $fecha ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
