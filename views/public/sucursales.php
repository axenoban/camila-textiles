<!-- views/public/sucursales.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-content">
    <section class="section">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-7">
                    <h1 class="section-title text-start mb-3">Sucursales estratégicas</h1>
                    <p class="text-muted mb-0">Coordinamos inventarios y reservas desde un sistema central para abastecer a nuestros clientes en toda la ciudad.</p>
                </div>
                <div class="col-lg-5 text-lg-end mt-4 mt-lg-0">
                    <span class="badge-soft">Atención personalizada y retiro inmediato</span>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="branch-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Sucursal Santa Cruz 1</h5>
                            <span class="badge-soft">Central</span>
                        </div>
                        <p class="text-muted mb-1"><i class="bi bi-geo-alt text-primary me-2"></i>Av. Las Américas 123, Santa Cruz</p>
                        <p class="text-muted mb-1"><i class="bi bi-telephone text-primary me-2"></i>591-343-4567</p>
                        <p class="text-muted mb-0"><i class="bi bi-clock text-primary me-2"></i>Lunes a viernes: 9:00 - 18:00</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="branch-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Sucursal Santa Cruz 2</h5>
                            <span class="badge-soft">Logística</span>
                        </div>
                        <p class="text-muted mb-1"><i class="bi bi-geo-alt text-primary me-2"></i>Calle 21 de Mayo, Santa Cruz</p>
                        <p class="text-muted mb-1"><i class="bi bi-telephone text-primary me-2"></i>591-343-7890</p>
                        <p class="text-muted mb-0"><i class="bi bi-clock text-primary me-2"></i>Lunes a viernes: 10:00 - 17:00</p>
                    </div>
                </div>
            </div>
            <div class="feature-card mt-5">
                <div class="row align-items-center g-4">
                    <div class="col-md-8">
                        <h4 class="fw-semibold mb-2">Coordinación en tiempo real</h4>
                        <p class="text-muted mb-0">El sistema gestiona transferencias entre sucursales según la demanda de cada cliente, optimizando tiempos de entrega y disponibilidad.</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a class="btn btn-primary rounded-pill px-4" href="<?= BASE_URL ?>/views/public/contacto.php">Agendar visita</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include('includes/footer.php'); ?>
