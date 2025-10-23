<?php
require_once __DIR__ . '/../../models/sucursal.php';

// Crear una instancia del modelo de sucursal
$sucursalModel = new Sucursal();

// Obtener solo las sucursales que estén visibles
$sucursales = $sucursalModel->obtenerSucursalesVisibles();
?>

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
                <?php if (!empty($sucursales)): ?>
                    <?php foreach ($sucursales as $sucursal): ?>
                        <div class="col-md-6">
                            <div class="branch-card">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5><?= htmlspecialchars($sucursal['nombre']); ?></h5>
                                    <span class="badge-soft"><?= htmlspecialchars($sucursal['visible'] ? 'Central' : 'Logística'); ?></span>
                                </div>
                                <p class="text-muted mb-1"><i class="bi bi-geo-alt text-primary me-2"></i><?= htmlspecialchars($sucursal['direccion']); ?></p>
                                <p class="text-muted mb-1"><i class="bi bi-telephone text-primary me-2"></i><?= htmlspecialchars($sucursal['telefono']); ?></p>
                                <p class="text-muted mb-0"><i class="bi bi-clock text-primary me-2"></i><?= htmlspecialchars($sucursal['horario_apertura']); ?></p>

                                <!-- Mostrar el mapa de la sucursal -->
                                <?php if ($sucursal['latitud'] && $sucursal['longitud']): ?>
                                    <div class="mt-3">
                                        <div id="map-<?= $sucursal['id']; ?>" style="height: 200px; width: 100%;"></div>
                                    </div>
                                    <script>
                                        // Inicializar el mapa para cada sucursal usando Leaflet
                                        function initMap<?= $sucursal['id']; ?>() {
                                            const location = { lat: <?= $sucursal['latitud']; ?>, lng: <?= $sucursal['longitud']; ?> };

                                            const map = L.map('map-<?= $sucursal['id']; ?>').setView([location.lat, location.lng], 12);

                                            // Capa de OpenStreetMap
                                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                                            }).addTo(map);

                                            // Crear un marcador en la ubicación
                                            L.marker([location.lat, location.lng]).addTo(map)
                                                .bindPopup("<?= htmlspecialchars($sucursal['nombre']); ?>")
                                                .openPopup();
                                        }

                                        // Llamar a la función para inicializar el mapa
                                        document.addEventListener('DOMContentLoaded', function () {
                                            initMap<?= $sucursal['id']; ?>();
                                        });
                                    </script>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <p class="text-muted text-center">No hay sucursales disponibles actualmente.</p>
                    </div>
                <?php endif; ?>
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

<!-- Cargar Leaflet y el script de OpenStreetMap -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
