<!-- views/admin/agregar_sucursal.php -->
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <header class="page-header text-center text-lg-start mb-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h1 class="page-title mb-2">Nueva Sucursal</h1>
                    <p class="page-subtitle mb-0 text-muted">Registra una nueva sucursal con todos los datos requeridos.</p>
                </div>
                <a href="sucursales.php" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </header>

        <!-- Formulario para agregar sucursal -->
        <div class="portal-form mb-5">
            <h5 class="fw-semibold mb-3"><i class="bi bi-plus-circle me-2"></i>Agregar nueva sucursal</h5>
            <form action="<?= BASE_URL ?>/controllers/sucursales.php" method="POST" class="row g-4">
                <input type="hidden" name="accion" value="crear">

                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre de la sucursal</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>

                <div class="col-md-6">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" required>
                </div>

                <div class="col-md-6">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" required>
                </div>

                <div class="col-md-6">
                    <label for="horario_apertura" class="form-label">Horario de apertura</label>
                    <input type="text" class="form-control" id="horario_apertura" name="horario_apertura" placeholder="Ej: Lunes a Viernes: 9:00 AM - 6:00 PM" required>
                </div>

                <!-- Mapa y coordenadas -->
                <div class="col-md-12">
                    <label for="ubicacion" class="form-label">Ubicación en el mapa</label>
                    <div id="map" style="height: 300px;"></div>
                    <input type="hidden" id="latitud" name="latitud">
                    <input type="hidden" id="longitud" name="longitud">
                    <input type="hidden" id="direccion_mapa" name="direccion_mapa"> <!-- Dirección para la base de datos -->
                    <small class="text-muted">Seleccione la ubicación de la sucursal en el mapa.</small>
                </div>

                <div class="col-md-6">
                    <label for="visible" class="form-label">¿Mostrar en la página pública?</label>
                    <select class="form-select" id="visible" name="visible">
                        <option value="1" selected>Visible</option>
                        <option value="0">Oculto</option>
                    </select>
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Agregar sucursal
                    </button>
                </div>
            </form>
        </div>

    </div>
</main>

<!-- Leaflet Map Script -->
<script>
    let map;
    let marker;

    // Coordenadas iniciales de Santa Cruz de la Sierra, Bolivia
    const defaultLatLng = { lat: -17.7795, lng: -63.1823 };  

    // Inicialización del mapa con Leaflet
    function initMap() {
        map = L.map('map').setView([defaultLatLng.lat, defaultLatLng.lng], 12);

        // Capa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Marcador inicial en Santa Cruz
        marker = L.marker([defaultLatLng.lat, defaultLatLng.lng]).addTo(map);
        
        // Actualizar las coordenadas cuando el marcador es movido
        marker.on('dragend', function(e) {
            const position = marker.getLatLng();
            document.getElementById('latitud').value = position.lat;
            document.getElementById('longitud').value = position.lng;
            obtenerDireccion(position.lat, position.lng); // Obtener dirección cuando se mueve el marcador
        });

        // Cuando se hace clic en el mapa, actualizar el marcador y obtener la dirección
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('latitud').value = e.latlng.lat;
            document.getElementById('longitud').value = e.latlng.lng;
            obtenerDireccion(e.latlng.lat, e.latlng.lng); // Obtener dirección
        });
    }

    // Función para obtener la dirección desde las coordenadas usando geocodificación inversa (OpenStreetMap)
    function obtenerDireccion(lat, lng) {
        const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data.display_name) {
                    document.getElementById('direccion_mapa').value = data.display_name; // Establecer la dirección en el campo oculto
                }
            })
            .catch(error => console.error('Error al obtener la dirección:', error));
    }

    // Cargar el mapa cuando la página esté lista
    window.onload = initMap;
</script>

<?php include('includes/footer.php'); ?>
