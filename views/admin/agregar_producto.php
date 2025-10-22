<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<main class="main-wrapper">
    <div class="container-fluid px-4 px-lg-5">
        <header class="page-header text-center text-lg-start">
            <h1 class="page-title">Agregar nuevo producto</h1>
            <p class="page-subtitle">Registra textiles con su ficha técnica, precio y fotografía para mantener el catálogo actualizado.</p>
        </header>
        <div class="row justify-content-center">
            <div class="col-12 col-xl-8">
                <div class="form-shell">
                    <form action="<?= BASE_URL ?>/controllers/productos.php" method="POST" enctype="multipart/form-data" class="row g-4">
                        <input type="hidden" name="accion" value="crear">
                        
                        <div class="col-12">
                            <label for="nombre" class="form-label">Nombre del producto</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        
                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="ancho_metros" class="form-label">Ancho en metros</label>
                            <input type="number" class="form-control" id="ancho_metros" name="ancho_metros" step="0.01" value="1.60" required>
                        </div>

                        <div class="col-md-6">
                            <label for="composicion" class="form-label">Composición</label>
                            <input type="text" class="form-control" id="composicion" name="composicion" required>
                        </div>

                        <div class="col-md-6">
                            <label for="tipo_tela" class="form-label">Tipo de tela</label>
                            <input type="text" class="form-control" id="tipo_tela" name="tipo_tela" required>
                        </div>

                        <div class="col-md-6">
                            <label for="elasticidad" class="form-label">Elasticidad</label>
                            <input type="text" class="form-control" id="elasticidad" name="elasticidad" required>
                        </div>

                        <div class="col-md-6">
                            <label for="precio_metro" class="form-label">Precio por metro (USD)</label>
                            <input type="number" class="form-control" id="precio_metro" name="precio_metro" step="0.01" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="precio_rollo" class="form-label">Precio por rollo (USD)</label>
                            <input type="number" class="form-control" id="precio_rollo" name="precio_rollo" step="0.01" required>
                        </div>

                        <div class="col-md-6">
                            <label for="metros_por_rollo" class="form-label">Total de metros por rollo (aproximado)</label>
                            <input type="number" class="form-control" id="metros_por_rollo" name="metros_por_rollo" step="0.01" required>
                            <small class="form-text text-muted">Este es un valor aproximado de metros por rollo.</small>
                        </div>

                        <!-- Campo para URL de imagen -->
                        <div class="col-12">
                            <label for="imagen" class="form-label">URL de imagen (opcional si sube imagen local)</label>
                            <input type="url" class="form-control" id="imagen" name="imagen" placeholder="URL de la imagen">
                        </div>

                        <!-- Campo para subir imagen local -->
                        <div class="col-12">
                            <label for="imagen_local" class="form-label">Subir imagen local (opcional)</label>
                            <input type="file" class="form-control" id="imagen_local" name="imagen_local" accept="image/*">
                            <small class="form-text text-muted">Si se sube una imagen local, la URL de imagen no es necesaria.</small>
                        </div>

                        <div class="col-12 text-end">
                            <a href="productos.php" class="btn btn-outline-primary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-success">Guardar producto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
