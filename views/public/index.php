<!-- views/public/index.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-content">
    <section class="hero">
        <div class="container">
            <div class="row align-items-center gy-5">
                <div class="col-lg-6">
                    <span class="badge-soft">Textiles premium importados</span>
                    <h1 class="hero-title mt-3">Gestión inteligente para una empresa textil en constante crecimiento</h1>
                    <p class="hero-subtitle">Digitalizamos el inventario, los pedidos y la experiencia de compra de Camila Textil para conectar en tiempo real a los clientes con las telas que buscan.</p>
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a class="btn btn-primary btn-lg rounded-pill px-4" href="/camila-textil/views/public/productos.php">Explorar catálogo</a>
                        <a class="btn btn-outline-primary rounded-pill px-4" href="/camila-textil/views/public/acerca.php">Conocer la empresa</a>
                    </div>
                    <div class="hero-metrics">
                        <div class="metric-card">
                            <span class="metric-label">Referencias activas</span>
                            <span class="metric-value">+120</span>
                        </div>
                        <div class="metric-card">
                            <span class="metric-label">Stock en tiempo real</span>
                            <span class="metric-value">99.3%</span>
                        </div>
                        <div class="metric-card">
                            <span class="metric-label">Clientes fidelizados</span>
                            <span class="metric-value">2K+</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-illustration">
                        <img src="https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=900&q=80" class="img-fluid rounded-4" alt="Rollos de tela">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <h2 class="section-title">¿Por qué Camila Textil?</h2>
            <p class="section-lead">Automatizamos cada etapa de la venta de telas: desde el control de inventarios hasta la reserva en línea para diseñadores y mayoristas.</p>
            <div class="row g-4 mt-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-boxes"></i></div>
                        <h5 class="fw-semibold">Inventarios confiables</h5>
                        <p class="mb-0">Actualizaciones en tiempo real que evitan rupturas de stock y mantienen sincronizadas todas las sucursales.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-cart-check"></i></div>
                        <h5 class="fw-semibold">Pedidos ágiles</h5>
                        <p class="mb-0">Los clientes pueden reservar online y pagar en tienda o vía digital, con seguimiento completo del estado.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-graph-up-arrow"></i></div>
                        <h5 class="fw-semibold">Datos para decidir</h5>
                        <p class="mb-0">Reportes de ventas, ganancias y rotación de stock para proyectar compras y maximizar la rentabilidad.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section pt-0">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                <div>
                    <h2 class="section-title text-start mb-0">Productos destacados</h2>
                    <p class="text-muted mb-0">Una selección con la textura, los colores y el rendimiento que tus proyectos necesitan.</p>
                </div>
                <a class="btn btn-outline-primary rounded-pill px-4 mt-3 mt-md-0" href="/camila-textil/views/public/productos.php">Ver todo el catálogo</a>
            </div>
            <div class="product-grid">
                <?php foreach ($productos as $producto): ?>
                    <article class="product-card">
                        <img src="<?= $producto['imagen'] ?>" alt="<?= $producto['nombre'] ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0"><?= $producto['nombre'] ?></h5>
                                <span class="product-price">$<?= number_format($producto['precio'], 2) ?></span>
                            </div>
                            <p class="card-text mb-4"><?= $producto['descripcion'] ?></p>
                            <a href="/camila-textil/views/public/productos.php" class="btn btn-primary w-100">Explorar más opciones</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section pt-0">
        <div class="container">
            <div class="feature-card text-center px-4 py-5">
                <h3 class="fw-semibold mb-3">Integra todo el flujo de trabajo textil en un solo lugar</h3>
                <p class="text-muted mb-4">El sistema web de Camila Textil conecta a los equipos de ventas, inventarios y atención al cliente para ofrecer respuestas inmediatas y una experiencia memorable.</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <span class="badge-soft">Reservas en línea</span>
                    <span class="badge-soft">Alertas inteligentes de stock</span>
                    <span class="badge-soft">Panel administrativo con reportes</span>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include('includes/footer.php'); ?>
