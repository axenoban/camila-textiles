<!-- views/public/contacto.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-content contact-section">
    <section class="section pt-0">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8">
                    <h1 class="section-title text-start mb-3">Conversemos sobre tu próximo proyecto textil</h1>
                    <p class="text-muted mb-0">Nuestro equipo comercial está listo para asesorarte en selección de telas, reservas y logística personalizada para entregas dentro y fuera de Santa Cruz.</p>
                </div>
            </div>
            <div class="contact-wrapper">
                <div class="contact-card">
                    <h5 class="fw-semibold mb-3">Información de contacto</h5>
                    <ul class="ps-0 mb-0">
                        <li><i class="bi bi-telephone"></i> +591 700 00000</li>
                        <li><i class="bi bi-envelope"></i> contacto@camilatextil.com</li>
                        <li><i class="bi bi-geo-alt"></i> Av. Las Américas 123, Santa Cruz, Bolivia</li>
                        <li><i class="bi bi-clock"></i> Lunes a viernes: 9:00 - 18:00</li>
                    </ul>
                    <div class="mt-4">
                        <h6 class="text-uppercase text-muted small">Atención personalizada</h6>
                        <p class="text-muted mb-0">Coordinamos reuniones virtuales o presenciales para mayoristas y diseñadores independientes.</p>
                    </div>
                </div>
                <div class="form-card">
                    <h5 class="fw-semibold mb-3">Escríbenos</h5>
                    <form action="enviar_contacto.php" method="POST" class="row g-4">
                        <div class="col-12 col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre completo" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="correo@ejemplo.com" required>
                        </div>
                        <div class="col-12">
                            <label for="mensaje" class="form-label">Mensaje</label>
                            <textarea class="form-control" id="mensaje" name="mensaje" rows="4" placeholder="Cuéntanos lo que necesitas" required></textarea>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Enviar mensaje</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include('includes/footer.php'); ?>
