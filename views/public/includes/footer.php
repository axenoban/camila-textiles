<!-- views/public/includes/footer.php -->
<footer class="site-footer pt-5 pb-4 mt-auto">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="footer-title">Camila Textil</h5>
                <p class="footer-text">Importamos telas de alta gama para proyectos que buscan calidad, innovación y acabados impecables.</p>
            </div>
            <div class="col-md-4">
                <h6 class="footer-heading">Enlaces rápidos</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="<?= BASE_URL ?>/views/public/index.php">Inicio</a></li>
                    <li><a href="<?= BASE_URL ?>/views/public/productos.php">Catálogo</a></li>
                    <li><a href="<?= BASE_URL ?>/views/public/acerca.php">Nosotros</a></li>
                    <li><a href="<?= BASE_URL ?>/views/public/contacto.php">Contacto</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="footer-heading">Contáctanos</h6>
                <ul class="list-unstyled footer-contact">
                    <li><i class="bi bi-geo-alt"></i> Santa Cruz, Bolivia</li>
                    <li><i class="bi bi-telephone"></i> +591 700 00000</li>
                    <li><i class="bi bi-envelope"></i> ventas@camilatextil.com</li>
                </ul>
                <div class="d-flex gap-3 mt-3">
                    <a class="social-link" href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a class="social-link" href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a class="social-link" href="#" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>
        </div>
        <hr class="footer-divider my-4">
        <div class="d-flex flex-column flex-md-row justify-content-between text-muted small">
            <span>© <?php echo date('Y'); ?> Camila Textil. Todos los derechos reservados.</span>
            <span>Diseño profesional y automatización para la industria textil.</span>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.alert[data-auto-dismiss="true"]').forEach(alertEl => {
            const instance = bootstrap.Alert.getOrCreateInstance(alertEl);
            setTimeout(() => instance.close(), 3000);
        });
    });
</script>
</body>
</html>
