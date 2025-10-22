<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../database/conexion.php';
require_once __DIR__ . '/../../models/Usuario.php';

$usuarioModel = new Usuario();
$errores = [];
$status = null;

// --- LOGIN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $clave = $_POST['clave'] ?? '';

    if ($email === '' || $clave === '') {
        $errores[] = 'Ingresa tu correo electrónico y contraseña.';
    } else {
        // Autenticación del usuario
        $resultado = $usuarioModel->autenticarUsuario($email, $clave);

        // Verificamos si el resultado es un array (usuario encontrado)
        if (is_array($resultado)) {
            // Si el estado del usuario no es 'habilitado', mostrar mensaje de cuenta bloqueada
            if ($resultado['estado'] !== 'habilitado') {
                $errores[] = 'Tu cuenta ha sido bloqueada. Por favor, contacta con la administración.';
            } else {
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
        } elseif ($resultado === false) {
            // Si las credenciales no coinciden con los registros
            $errores[] = 'Las credenciales no coinciden con nuestros registros.';
        } else {
            // Si es un mensaje de error específico como 'Cuenta bloqueada'
            $errores[] = $resultado; // Aquí mostramos el mensaje específico
        }
    }
}



// --- REGISTRO ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $clave = $_POST['clave'] ?? '';
    $confirmar_clave = $_POST['confirmar_clave'] ?? '';

    if ($nombre && $email && $clave && $confirmar_clave) {
        if ($clave !== $confirmar_clave) {
            $errores[] = 'Las contraseñas no coinciden.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El correo electrónico no es válido.';
        } else {
            try {
                $usuarioModel->crearUsuario($nombre, $email, $clave, 'cliente');
                $status = 'registro_exitoso';
            } catch (PDOException $e) {
                if (str_contains($e->getMessage(), 'Duplicate entry')) {
                    $errores[] = 'Este correo ya está registrado.';
                } else {
                    $errores[] = 'Error al registrar el usuario.';
                }
            }
        }
    } else {
        $errores[] = 'Por favor completa todos los campos.';
    }
}

include('includes/header.php');
include('includes/navbar.php');
?>

<main class="auth-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <section class="auth-card shadow-sm">
                    <div class="text-center mb-4">
                        <span class="brand-mark brand-mark-lg mb-3">CT</span>
                        <h1 class="auth-title" id="form-title">Iniciar sesión</h1>
                        <p class="auth-subtitle">Accede al panel de Camila Textil con tus credenciales registradas.</p>
                    </div>

                    <!-- Mensajes -->
                    <?php if (!empty($errores)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= implode('<br>', $errores); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    <?php elseif ($status === 'registro_exitoso'): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Te has registrado correctamente. Ahora puedes iniciar sesión.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    <?php endif; ?>

                    <!-- LOGIN -->
                    <form method="POST" class="auth-form" id="login-form">
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico o usuario</label>
                            <input type="text" class="form-control" id="email" name="email" required autofocus>
                        </div>
                        <div class="mb-4 position-relative">
                            <label for="clave" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="clave" name="clave" required>
                            <button class="toggle-password" type="button" aria-label="Mostrar u ocultar contraseña">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" name="login">Ingresar</button>
                    </form>

                    <!-- REGISTRO -->
                    <form method="POST" class="auth-form" id="register-form" style="display: none;">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="email_registro" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="email_registro" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="clave_registro" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="clave_registro" name="clave" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmar_clave" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="confirmar_clave" name="confirmar_clave" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" name="register">Registrarse</button>
                    </form>

                    <div class="mt-4 text-center">
                        <a class="auth-link" href="javascript:void(0);" id="toggle-form">¿No tienes cuenta? Regístrate</a>
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>

<script>
    // Alternar entre login y registro
    document.getElementById('toggle-form').addEventListener('click', function() {
        var loginForm = document.getElementById('login-form');
        var registerForm = document.getElementById('register-form');
        var formTitle = document.getElementById('form-title');
        var toggleLink = document.getElementById('toggle-form');

        if (loginForm.style.display === 'none') {
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
            formTitle.textContent = 'Iniciar sesión';
            toggleLink.textContent = '¿No tienes cuenta? Regístrate';
        } else {
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
            formTitle.textContent = 'Regístrate';
            toggleLink.textContent = '¿Ya tienes cuenta? Inicia sesión';
        }
    });

    // Mostrar / ocultar contraseña
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = btn.previousElementSibling;
            input.type = input.type === 'password' ? 'text' : 'password';
            btn.querySelector('i').classList.toggle('bi-eye');
            btn.querySelector('i').classList.toggle('bi-eye-slash');
        });
    });
</script>

<style>
    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
    }

    .auth-card {
        background: #fff;
        padding: 2rem;
        border-radius: 12px;
    }

    .auth-title {
        font-weight: 700;
    }

    .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6c757d;
    }

    .auth-link {
        color: #0d6efd;
        text-decoration: none;
    }

    .auth-link:hover {
        text-decoration: underline;
    }
</style>
