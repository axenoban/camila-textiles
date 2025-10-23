<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/../../models/producto.php';
require_once __DIR__ . '/../../models/pedido.php';
$productoModel = new Producto();
$pedidoModel = new Pedido();
$productos = $productoModel->obtenerProductosVisibles();
// 🔹 Simulamos popularidad: cantidad de pedidos por producto
foreach ($productos as &$p) {
    $p['cantidad_pedidos'] = $pedidoModel->contarPedidosPorProducto($p['id']) ?? 0;
}
unset($p);
?><!-- views/cliente/productos.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-content">
    <section class="section py-5">
        <div class="container">
            <!-- 🧭 Encabezado -->
            <div class="text-center mb-5">
                <h1 class="section-title mb-3">Catálogo Exclusivo para Clientes</h1>
                <p class="section-lead mx-auto" style="max-width:720px;">
                    Explora nuestro catálogo de telas disponibles con precios preferenciales, actualizados en tiempo real.
                    Cada textil ha sido evaluado por calidad y disponibilidad.
                </p>
            </div>
            <!-- 🎚️ Filtros funcionales -->
            <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
                <button class="btn btn-outline-primary rounded-pill px-4 filtro" data-filtro="todos">Todos</button>
                <button class="btn btn-outline-primary rounded-pill px-4 filtro" data-filtro="nuevo">Nuevos</button>
                <button class="btn btn-outline-primary rounded-pill px-4 filtro" data-filtro="popular">Populares</button>
                <button class="btn btn-outline-primary rounded-pill px-4 filtro" data-filtro="premium">Premium</button>
            </div>
            <!-- 🧵 Cuadrícula de productos -->
            <div class="row g-4" id="productos-grid">
                <?php if (!empty($productos)): ?>
                    <?php foreach ($productos as $producto):
                        $fechaCreacion = strtotime($producto['fecha_creacion'] ?? '2025-01-01');
                        $esNuevo = (time() - $fechaCreacion) < (30 * 24 * 60 * 60); // Menos de 30 días
                        $esPopular = ($producto['cantidad_pedidos'] ?? 0) >= 5;
                        $esPremium = strpos(strtolower($producto['nombre']), 'premium') !== false;
                    ?>
                        <div class="col-md-6 col-lg-4 producto-card"
                            data-nuevo="<?= $esNuevo ? '1' : '0'; ?>"
                            data-popular="<?= $esPopular ? '1' : '0'; ?>"
                            data-premium="<?= $esPremium ? '1' : '0'; ?>">
                            <article class="card product-card shadow-sm border-0 h-100">
                                <!-- Imagen del producto -->
                                <div class="position-relative">
                                    <?php if (filter_var($producto['imagen_principal'], FILTER_VALIDATE_URL)): ?>
                                        <!-- Si es una URL, mostrarla directamente -->
                                        <img src="<?= htmlspecialchars($producto['imagen_principal'], ENT_QUOTES, 'UTF-8'); ?>"
                                            class="card-img-top rounded-top"
                                            alt="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                                            style="height: 260px; object-fit: cover;">
                                    <?php else: ?>
                                        <!-- Si es una ruta local, mostrarla desde el directorio de uploads -->
                                        <img src="<?= BASE_URL . '/uploads/' . basename($producto['imagen_principal']); ?>"
                                            class="card-img-top rounded-top"
                                            alt="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                                            style="height: 260px; object-fit: cover;">
                                    <?php endif; ?>

                                    <!-- Etiquetas -->
                                    <?php if ($esNuevo): ?>
                                        <span class="badge bg-info position-absolute top-0 start-0 m-3">Nuevo</span>
                                    <?php endif; ?>
                                    <?php if ($esPopular): ?>
                                        <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-3">Popular</span>
                                    <?php elseif ($esPremium): ?>
                                        <span class="badge bg-purple position-absolute top-0 end-0 m-3">Premium</span>
                                    <?php endif; ?>
                                </div>
                                <!-- Contenido -->
                                <div class="card-body d-flex flex-column">
                                    <h5 class="fw-semibold mb-2 text-dark"><?= htmlspecialchars($producto['nombre']); ?></h5>

                                    <p class="text-muted small flex-grow-1 mb-3">
                                        <?= htmlspecialchars($producto['descripcion']); ?>
                                    </p>

                                    <!-- Precios -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-primary fw-bold">
                                                Bs <?= number_format($producto['precio_metro'], 2, ',', '.'); ?> /m
                                            </span>
                                            <small class="text-muted">
                                                Rollo: Bs <?= number_format($producto['precio_rollo'], 2, ',', '.'); ?>
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Botón único -->
                                    <a href="<?= BASE_URL ?>/views/cliente/detalle_producto_cliente.php?id=<?= (int)$producto['id']; ?>"
                                        class="btn btn-primary w-100 rounded-pill">
                                        Realizar pedido
                                    </a>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <div class="empty-icon fs-1 mb-3"><i class="bi bi-emoji-neutral"></i></div>
                        <h5 class="fw-semibold mb-2">No hay productos disponibles</h5>
                        <p class="text-muted mb-0">Los productos publicados por el administrador aparecerán automáticamente aquí.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- 💡 Banner -->
            <div class="mt-5 p-4 bg-light rounded-4 text-center shadow-sm">
                <h5 class="fw-semibold mb-2 text-primary">¿Buscas inspiración para tu próxima colección?</h5>
                <p class="text-muted mb-3">Explora nuestras tendencias textiles o solicita una asesoría personalizada.</p>
                <a href="#" class="btn btn-outline-primary rounded-pill px-4">Ver tendencias</a>
            </div>
        </div>
    </section>
</main>

<?php include('includes/footer.php'); ?>

<style>
    .product-card {
        transition: all 0.25s ease-in-out;
        border-radius: 0.75rem;
        background: #fff;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.5em 0.75em;
    }

    .bg-purple {
        background-color: #6f42c1 !important;
        color: #fff;
    }

    .section-title {
        font-weight: 700;
        color: #212529;
    }

    .section-lead {
        color: #6c757d;
    }

    .btn-primary {
        background: linear-gradient(90deg, #0d6efd, #0dcaf0);
        border: none;
    }

    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: #fff;
    }

    .filtro.active {
        background: #0d6efd;
        color: #fff;
    }
</style>

<script>
    // 🧭 Filtro dinámico sin recargar
    document.querySelectorAll('.filtro').forEach(btn => {
        btn.addEventListener('click', e => {
            document.querySelectorAll('.filtro').forEach(b => b.classList.remove('active'));
            e.target.classList.add('active');

            const filtro = e.target.dataset.filtro;
            document.querySelectorAll('.producto-card').forEach(card => {
                const nuevo = card.dataset.nuevo === '1';
                const popular = card.dataset.popular === '1';
                const premium = card.dataset.premium === '1';

                if (
                    filtro === 'todos' ||
                    (filtro === 'nuevo' && nuevo) ||
                    (filtro === 'popular' && popular) ||
                    (filtro === 'premium' && premium)
                ) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Activa el filtro "Todos" al inicio
    document.querySelector('.filtro[data-filtro="todos"]').classList.add('active');
</script>
