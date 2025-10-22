<?php
require_once __DIR__ . '/../../database/conexion.php';

// Recuperar los comentarios desde la base de datos
$stmt = $pdo->prepare("SELECT * FROM comentarios ORDER BY id DESC");
$stmt->execute();
$comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Eliminar comentario
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM comentarios WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header("Location: comentarios.php");
    exit;
}

// Marcar comentario como leído
if (isset($_GET['leer'])) {
    $id = (int)$_GET['leer'];
    $stmt = $pdo->prepare("UPDATE comentarios SET estado = 'leído' WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header("Location: comentarios.php");
    exit;
}
?>

<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4">
        <h1 class="page-title">Comentarios</h1>
        <p class="text-muted">Aquí puedes gestionar los comentarios enviados desde el formulario de contacto.</p>

        <table class="table table-modern">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Mensaje</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comentarios as $comentario): ?>
                    <tr>
                        <td><?= htmlspecialchars($comentario['nombre']); ?></td>
                        <td><?= htmlspecialchars($comentario['email']); ?></td>
                        <td><?= htmlspecialchars($comentario['mensaje']); ?></td>
                        <td>
                            <span class="status-pill <?= $comentario['estado'] == 'leído' ? 'status-confirmado' : 'status-pendiente'; ?>">
                                <?= ucfirst($comentario['estado']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($comentario['estado'] == 'sin leer'): ?>
                                <a href="comentarios.php?leer=<?= $comentario['id']; ?>" class="btn btn-success btn-sm">Marcar como leído</a>
                            <?php endif; ?>
                            <a href="comentarios.php?eliminar=<?= $comentario['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include('includes/footer.php'); ?>
