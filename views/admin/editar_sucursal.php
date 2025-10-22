<?php 
require_once __DIR__ . '/../../config/app.php'; 
require_once __DIR__ . '/../../models/sucursal.php';

// Comprobamos si la sesión está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario es un administrador
if (empty($_SESSION['usuario']) || ($_SESSION['rol'] ?? null) !== 'administrador') {
    header('Location: ' . BASE_URL . '/views/public/login.php');
    exit;
}

$sucursalModel = new Sucursal();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: ' . BASE_URL . '/views/admin/sucursales.php?status=no_encontrado');
    exit;
}

$sucursal = $sucursalModel->obtenerSucursalPorId($id);

if (!$sucursal) {
    header('Location: ' . BASE_URL . '/views/admin/sucursales.php?status=no_encontrado');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Actualizar la sucursal
    $nombre = trim($_POST['nombre']);
    $direccion = trim($_POST['direccion']);
    $telefono = trim($_POST['telefono']);
    $horario_apertura = trim($_POST['horario_apertura']);
    $latitud = trim($_POST['latitud']);
    $longitud = trim($_POST['longitud']);
    $visible = isset($_POST['visible']) ? 1 : 0;

    if ($nombre && $direccion && $telefono && $horario_apertura && $latitud && $longitud) {
        $sucursalModel->editarSucursal($id, $nombre, $direccion, $telefono, $horario_apertura, $latitud, $longitud, $visible);
        header('Location: ' . BASE_URL . '/views/admin/sucursales.php?status=actualizado');
        exit;
    } else {
        $error = 'Todos los campos son obligatorios.';
    }
}
?>

<?php include(__DIR__ . '/includes/header.php'); ?>
<?php include(__DIR__ . '/includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <header class="page-header text-center text-lg-start">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h1 class="page-title mb-2">Editar sucursal</h1>
                    <p class="page-subtitle mb-0">Actualiza la información de la sucursal seleccionada.</p>
                </div>
                <a href="sucursales.php" class="btn btn-outline-primary">Volver a sucursales</a>
            </div>
        </header>

        <div class="table-shell">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>
            <form action="editar_sucursal.php?id=<?= (int)$id; ?>" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la sucursal</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($sucursal['nombre'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" value="<?= htmlspecialchars($sucursal['direccion'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" value="<?= htmlspecialchars($sucursal['telefono'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="horario_apertura" class="form-label">Horario de apertura</label>
                    <input type="text" class="form-control" id="horario_apertura" name="horario_apertura" value="<?= htmlspecialchars($sucursal['horario_apertura'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <!-- Mapa y coordenadas -->
                <div class="mb-3">
                    <label for="ubicacion" class="form-label">Ubicación en el mapa</label>
                    <div id="map" style="height: 300px;"></div>
                    <input type="hidden" id="latitud" name="latitud" value="<?= htmlspecialchars($sucursal['latitud'], ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" id="longitud" name="longitud" value="<?= htmlspecialchars($sucursal['longitud'], ENT_QUOTES, 'UTF-8'); ?>">
                    <small class="text-muted">Arrastra el marcador para actualizar la ubicación de la sucursal.</small> <!-- Mensaje de instrucción -->
                </div>

                <div class="mb-3">
                    <label for="visible" class="form-label">¿Mostrar en la página pública?</label>
                    <select class="form-select" id="visible" name="visible">
                        <option value="1" <?= $sucursal['visible'] ? 'selected' : ''; ?>>Visible</option>
                        <option value="0" <?= !$sucursal['visible'] ? 'selected' : ''; ?>>Oculto</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar sucursal</button>
            </form>
        </div>
    </div>
</main>

<!-- Cargar Leaflet -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
    let map;
    let marker;

    // Inicialización del mapa para la sucursal seleccionada
    function initMap() {
        const lat = parseFloat("<?= $sucursal['latitud'] ?>");
        const lng = parseFloat("<?= $sucursal['longitud'] ?>");

        if (!isNaN(lat) && !isNaN(lng)) {
            const defaultLatLng = { lat: lat, lng: lng };

            map = L.map('map').setView([defaultLatLng.lat, defaultLatLng.lng], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Marcador arrastrable para modificar la ubicación
            marker = L.marker([defaultLatLng.lat, defaultLatLng.lng], { draggable: true }).addTo(map);

            // Actualizar las coordenadas cuando el marcador se mueva
            marker.on('dragend', function() {
                const position = marker.getLatLng();
                document.getElementById('latitud').value = position.lat;
                document.getElementById('longitud').value = position.lng;
            });
        }
    }

    window.onload = initMap;
</script>

<?php include(__DIR__ . '/includes/footer.php'); ?>
