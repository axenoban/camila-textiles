<!-- views/cliente/dashboard.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-area">
    <div class="container-fluid px-4 px-lg-5">
        <section class="client-section text-center text-lg-start">
            <h1 class="section-heading">Bienvenido a tu panel</h1>
            <p class="section-subtitle">Consulta pedidos recientes, descubre nuevas telas y actualiza tus datos en minutos.</p>
        </section>
        <div class="row g-4">
            <div class="col-12 col-lg-4">
                <div class="portal-card h-100 text-start">
                    <div class="icon-circle"><i class="bi bi-clock-history"></i></div>
                    <h5 class="fw-semibold">Historial de pedidos</h5>
                    <p class="text-muted">Revisa el estado de tus compras anteriores y descarga comprobantes.</p>
                    <a href="pedidos.php" class="btn btn-outline-primary mt-3">Ver historial</a>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="portal-card h-100 text-start">
                    <div class="icon-circle"><i class="bi bi-gem"></i></div>
                    <h5 class="fw-semibold">Productos disponibles</h5>
                    <p class="text-muted">Explora la colección actualizada con disponibilidad en tiempo real.</p>
                    <a href="productos.php" class="btn btn-success mt-3">Explorar catálogo</a>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="portal-card h-100 text-start">
                    <div class="icon-circle"><i class="bi bi-person-gear"></i></div>
                    <h5 class="fw-semibold">Gestiona tu perfil</h5>
                    <p class="text-muted">Actualiza preferencias de contacto y credenciales de acceso.</p>
                    <a href="perfil.php" class="btn btn-info mt-3">Configurar perfil</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
