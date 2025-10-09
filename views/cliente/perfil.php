<?php
require_once __DIR__ . '/includes/init.php';

$usuario = $clienteActual;
$mensajePerfil = $_SESSION['perfil_mensaje'] ?? null;
$tipoPerfil = $_SESSION['perfil_tipo'] ?? null;
unset($_SESSION['perfil_mensaje'], $_SESSION['perfil_tipo']);
?>
<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-area">
    <div class="container-fluid px-4 px-lg-5">
        <section class="client-section text-center text-lg-start">
            <h1 class="section-heading">Mi perfil</h1>
            <p class="section-subtitle">Mantén tus datos actualizados para agilizar pedidos, pagos y notificaciones.</p>
        </section>
        <?php if ($mensajePerfil): ?>
            <div class="alert alert-<?= $tipoPerfil === 'success' ? 'success' : 'danger'; ?>" role="alert">
                <?= htmlspecialchars($mensajePerfil, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>
        <div class="row justify-content-center">
            <div class="col-12 col-xl-6">
                <div class="portal-form">
                    <form action="<?= BASE_URL ?>/controllers/perfil.php" method="POST" class="row g-4">
                        <div class="col-12">
                            <label for="nombre" class="form-label">Nombre completo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="col-12">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario['email'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="col-12">
                            <label for="clave" class="form-label">Nueva contraseña <span class="text-muted small">(opcional)</span></label>
                            <input type="password" class="form-control" id="clave" name="clave" placeholder="••••••••">
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-success">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
