<?php $pageTitle = 'Agregar Producto'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Registrar Componente</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($errores)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errores as $e): ?><p class="mb-0"><?= htmlspecialchars($e) ?></p><?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="<?= BASE_URL ?>/productos/crear">
                    <div class="mb-3">
                        <label class="form-label">Descripción del producto <span class="text-danger">*</span></label>
                        <input type="text" name="descripcion" class="form-control"
                               value="<?= htmlspecialchars($_POST['descripcion'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select name="categoria_id" class="form-select">
                            <option value="">— Sin categoría —</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat['cod_categoria'] ?>"
                                    <?= (($_POST['categoria_id'] ?? '') == $cat['cod_categoria']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['detalle']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Guardar
                        </button>
                        <a href="<?= BASE_URL ?>/productos" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
