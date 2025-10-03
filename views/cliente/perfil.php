<!-- views/cliente/perfil.php -->
<?php include('../includes/header.php'); ?>
<?php include('../includes/navbar.php'); ?>

<div class="container mt-4">
    <h2 class="text-center">Mi Perfil</h2>
    <form action="actualizar_perfil.php" method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $usuario['nombre'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $usuario['email'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="clave" class="form-label">Nueva Contraseña</label>
            <input type="password" class="form-control" id="clave" name="clave" required>
        </div>
        <button type="submit" class="btn btn-success">Actualizar Perfil</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
