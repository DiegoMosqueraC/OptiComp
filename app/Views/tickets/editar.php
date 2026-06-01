<?php $pageTitle = 'Editar Ticket'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-7">
        <?php if (!$ticket): ?>
            <div class="alert alert-danger">Ticket no encontrado.</div>
        <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Actualizar Ticket #<?= $ticket['id'] ?></h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    <strong>Equipo:</strong> <?= htmlspecialchars($ticket['equipo']) ?> &nbsp;|&nbsp;
                    <strong>Cliente:</strong> <?= htmlspecialchars($ticket['nombre_cliente'] ?? 'N/A') ?>
                </p>
                <form method="POST" action="<?= BASE_URL ?>/tickets/editar/<?= $ticket['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select" required>
                            <option value="Abierto"    <?= $ticket['estado'] === 'Abierto'    ? 'selected' : '' ?>>Abierto</option>
                            <option value="En proceso" <?= $ticket['estado'] === 'En proceso' ? 'selected' : '' ?>>En proceso</option>
                            <option value="Cerrado"    <?= $ticket['estado'] === 'Cerrado'    ? 'selected' : '' ?>>Cerrado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha de Salida</label>
                        <input type="date" name="fecha_salida" class="form-control"
                               value="<?= $ticket['fecha_salida'] ?? '' ?>">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-secondary">
                            <i class="bi bi-check2 me-1"></i>Actualizar
                        </button>
                        <a href="<?= BASE_URL ?>/tickets" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
