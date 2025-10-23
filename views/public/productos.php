<?php
require_once __DIR__ . '/../../models/producto.php';

$productoModel = new Producto();
$productos = $productoModel->obtenerProductosVisibles();

// 🧭 Añadimos banderas visuales sin necesidad de sesión
foreach ($productos as &$p) {
    $fechaCreacion = strtotime($p['fecha_creacion'] ?? '2025-01-01');
    $p['es_nuevo'] = (time() - $fechaCreacion) < (30 * 24 * 60 * 60); // < 30 días
    $p['es_premium'] = strpos(strtolower($p['nombre']), 'premium') !== false;
}
unset($p);
?>
<!-- views/public/productos.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-content">
    <section class="section py-5">
        <div class="container">

            <!-- 🧭 Encabezado -->
            <div class="text-center mb-5">
                <h1 class="section-title mb-3">Catálogo Profesional de Telas</h1>
                <p class="section-lead mx-auto" style="max-width:720px;">
                    Explora nuestra colección textil con disponibilidad inmediata.<br>
                    Telas seleccionadas por calidad, textura y color, listas para tus proyectos.
                </p>
            </div>

            <!-- 🎚️ Filtros funcionales -->
            <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
                <button class="btn btn-outline-primary rounded-pill px-4 filtro" data-filtro="todos">Todos</button>
                <button class="btn btn-outline-primary rounded-pill px-4 filtro" data-filtro="nuevo">Nuevos</button>
                <button class="btn btn-outline-primary rounded-pill px-4 filtro" data-filtro="premium">Premium</button>
            </div>

            <!-- 🧵 Cuadrícula de productos -->
            <div class="row g-4" id="productos-grid">
                <?php if (!empty($productos)): ?>
                    <?php foreach ($productos as $producto): ?>
                        <div class="col-md-6 col-lg-4 producto-card"
                             data-nuevo="<?= $producto['es_nuevo'] ? '1' : '0'; ?>"
                             data-premium="<?= $producto['es_premium'] ? '1' : '0'; ?>">

                            <article class="card product-card shadow-sm border-0 h-100">
                                <!-- Imagen -->
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
                                    <?php if ($producto['es_nuevo']): ?>
                                        <span class="badge bg-info position-absolute top-0 start-0 m-3">Nuevo</span>
                                    <?php endif; ?>
                                    <?php if ($producto['es_premium']): ?>
                                        <span class="badge bg-purple position-absolute top-0 end-0 m-3">Premium</span>
                                    <?php endif; ?>
                                </div>

                                <!-- Contenido -->
                                <div class="card-body d-flex flex-column">
                                    <h5 class="fw-semibold mb-2 text-dark">
                                        <?= htmlspecialchars($producto['nombre']); ?>
                                    </h5>

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

                                    <!-- Botones -->
                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <a href="<?= BASE_URL ?>/views/public/detalle_producto.php?id=<?= (int)$producto['id']; ?>"
                                           class="btn btn-outline-primary btn-sm px-3 rounded-pill">
                                           Ver detalles
                                        </a>
                                        <a href="<?= BASE_URL ?>/views/public/login.php"
                                           class="btn btn-gradient btn-sm px-3 rounded-pill">
                                           Inicia sesión para reservar
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <div class="empty-icon fs-1 mb-3"><i class="bi bi-emoji-neutral"></i></div>
                        <h5 class="fw-semibold mb-2">No hay productos disponibles</h5>
                        <p class="text-muted mb-0">
                            Los productos registrados por el administrador aparecerán aquí automáticamente.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php include('includes/footer.php'); ?>

<style>
/* 🧵 Integración con estilos-publico.css */
.product-card {
    transition: all 0.25s ease-in-out;
    border-radius: 0.75rem;
    background: var(--surface);
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-card);
}
.badge {
    font-size: 0.75rem;
    padding: 0.5em 0.75em;
}
.bg-purple {
    background-color: #6f42c1 !important;
    color: #fff;
}
.btn-gradient {
    background: linear-gradient(90deg, #1b5bf7, #3b82f6);
    border: none;
    color: #fff;
}
.btn-gradient:hover {
    background: linear-gradient(90deg, #133bbf, #1d4ed8);
}
.filtro.active {
    background: var(--primary);
    color: #fff;
}
</style>

<script>
// 🧭 Filtro dinámico sin recargar (todos / nuevos / premium)
document.querySelectorAll('.filtro').forEach(btn => {
    btn.addEventListener('click', e => {
        document.querySelectorAll('.filtro').forEach(b => b.classList.remove('active'));
        e.target.classList.add('active');

        const filtro = e.target.dataset.filtro;
        document.querySelectorAll('.producto-card').forEach(card => {
            const nuevo = card.dataset.nuevo === '1';
            const premium = card.dataset.premium === '1';

            if (
                filtro === 'todos' ||
                (filtro === 'nuevo' && nuevo) ||
                (filtro === 'premium' && premium)
            ) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});

// activar "Todos" por defecto
document.querySelector('.filtro[data-filtro="todos"]').classList.add('active');
</script>
