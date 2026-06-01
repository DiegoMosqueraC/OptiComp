<?php $pageTitle = 'Nuevo Ticket'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Registrar Nuevo Ticket</h5>
            </div>
            <div class="card-body">

                <?php if (!empty($errores)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errores as $e): ?>
                                <li><?= htmlspecialchars($e) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= BASE_URL ?>/tickets/crear">
                    <div class="mb-3">
                        <label class="form-label">ID Cliente <span class="text-danger">*</span></label>
                        <input type="number" name="cliente_id" class="form-control"
                               value="<?= htmlspecialchars($_POST['cliente_id'] ?? '') ?>" required>
                        <div class="form-text">Ingrese el ID numérico del cliente registrado.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Equipo / Dispositivo <span class="text-danger">*</span></label>
                        <input type="text" name="equipo" class="form-control" maxlength="100"
                               value="<?= htmlspecialchars($_POST['equipo'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción del problema <span class="text-danger">*</span></label>
                        <textarea name="descripcion" class="form-control" rows="4" required><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado <span class="text-danger">*</span></label>
                        <select name="estado" class="form-select" required>
                            <option value="">— Seleccione —</option>
                            <option value="Abierto"    <?= (($_POST['estado'] ?? '') === 'Abierto')    ? 'selected' : '' ?>>Abierto</option>
                            <option value="En proceso" <?= (($_POST['estado'] ?? '') === 'En proceso') ? 'selected' : '' ?>>En proceso</option>
                            <option value="Cerrado"    <?= (($_POST['estado'] ?? '') === 'Cerrado')    ? 'selected' : '' ?>>Cerrado</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Guardar Ticket
                        </button>
                        <a href="<?= BASE_URL ?>/tickets" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
