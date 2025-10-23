<?php 
// Conexión a la base de datos
require_once __DIR__ . '/../../database/conexion.php';

// Procesar el formulario si se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $mensaje = $_POST['mensaje'] ?? '';

    // Verificar que todos los campos estén completos
    if ($nombre && $email && $mensaje) {
        try {
            // Preparar la consulta para insertar el mensaje en la base de datos
            $stmt = $pdo->prepare("INSERT INTO comentarios (nombre, email, mensaje) VALUES (:nombre, :email, :mensaje)");
            $stmt->execute([
                'nombre' => $nombre,
                'email' => $email,
                'mensaje' => $mensaje
            ]);

            // Redirigir con éxito
            $status = 'success';
        } catch (PDOException $e) {
            // En caso de error con la base de datos
            $status = 'error';
        }
    } else {
        // Si hay campos vacíos
        $status = 'error';
    }
}
?>

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

                <!-- Mostrar mensaje de éxito o error como notificación flotante -->
                <?php if (isset($status)): ?>
                    <div class="toast-container position-fixed bottom-0 end-0 p-3">
                        <div class="toast align-items-center text-white <?= ($status == 'success') ? 'bg-success' : 'bg-danger' ?>" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body">
                                    <?= ($status == 'success') ? 'Tu mensaje ha sido enviado con éxito. ¡Gracias!' : 'Hubo un error al enviar tu mensaje. Por favor, intenta nuevamente.' ?>
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>

                    <script>
                        // Asegurarse de que la notificación se muestre después de que el DOM se haya cargado
                        document.addEventListener("DOMContentLoaded", function() {
                            var toast = new bootstrap.Toast(document.querySelector('.toast'));
                            toast.show();
                        });
                    </script>
                <?php endif; ?>

                <div class="form-card">
                    <h5 class="fw-semibold mb-3">Escríbenos</h5>
                    <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" class="row g-4">
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

<!-- Cargar Bootstrap Toast JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
