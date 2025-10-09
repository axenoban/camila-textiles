<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../models/usuario.php';

if (isset($_SESSION['usuario'])) {
    $destino = ($_SESSION['rol'] ?? '') === 'administrador'
        ? BASE_URL . '/views/admin/dashboard.php'
        : BASE_URL . '/views/cliente/dashboard.php';
    header('Location: ' . $destino);
    exit;
}

$errores = [];
$identificador = '';

if (!empty($_SESSION['error_login'])) {
    $errores[] = $_SESSION['error_login'];
    unset($_SESSION['error_login']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identificador = trim($_POST['email'] ?? '');
    $clave = $_POST['clave'] ?? '';

    if ($identificador === '' || $clave === '') {
        $errores[] = 'Ingresa tu correo electrónico y contraseña.';
    } else {
        $usuarioModel = new Usuario();
        $resultado = $usuarioModel->autenticarUsuario($identificador, $clave);

        if ($resultado) {
            session_regenerate_id(true);
            $_SESSION['usuario'] = [
                'id' => $resultado['id'],
                'nombre' => $resultado['nombre'],
                'email' => $resultado['email'],
                'rol' => $resultado['rol'],
            ];
            $_SESSION['rol'] = $resultado['rol'];
            $destino = $resultado['rol'] === 'administrador'
                ? BASE_URL . '/views/admin/dashboard.php'
                : BASE_URL . '/views/cliente/dashboard.php';
            header('Location: ' . $destino);
            exit;
        }

        $errores[] = 'Las credenciales no coinciden con nuestros registros.';
    }
}

include('includes/header.php');
?>

<main class="auth-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <section class="auth-card">
                    <div class="text-center mb-4">
                        <span class="brand-mark brand-mark-lg mb-3">CT</span>
                        <h1 class="auth-title">Iniciar sesión</h1>
                        <p class="auth-subtitle">Accede al panel de Camila Textil con tus credenciales registradas.</p>
                    </div>
                    <?php if (!empty($errores)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= implode('<br>', $errores); ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" class="auth-form">
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico o usuario</label>
                            <input type="text" class="form-control" id="email" name="email" value="<?= htmlspecialchars($identificador, ENT_QUOTES, 'UTF-8'); ?>" required autofocus>
                        </div>
                        <div class="mb-4">
                            <label for="clave" class="form-label">Contraseña</label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="clave" name="clave" required>
                                <button class="toggle-password" type="button" aria-label="Mostrar u ocultar contraseña">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                    </form>
                    <div class="mt-4 text-center">
                        <a class="auth-link" href="<?= BASE_URL ?>/views/public/index.php">Volver al inicio</a>
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>

<script>
    const togglePasswordButton = document.querySelector('.toggle-password');
    const passwordField = document.getElementById('clave');

    if (togglePasswordButton && passwordField) {
        togglePasswordButton.addEventListener('click', () => {
            const isPassword = passwordField.type === 'password';
            passwordField.type = isPassword ? 'text' : 'password';
            togglePasswordButton.querySelector('i').classList.toggle('bi-eye');
            togglePasswordButton.querySelector('i').classList.toggle('bi-eye-slash');
        });
    }
</script>
