<?php $pageTitle = 'Clientes'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-people me-2"></i>Clientes Registrados</h4>
    <a href="<?= BASE_URL ?>/clientes/sincronizar" class="btn btn-outline-secondary btn-sm"
       onclick="return confirm('¿Sincronizar clientes desde la API externa?')">
        <i class="bi bi-arrow-repeat me-1"></i>Sincronizar API
    </a>
</div>

<?php if (isset($mensaje)): ?>
    <div class="alert alert-<?= $tipo === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show">
        <?= htmlspecialchars($mensaje) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (empty($clientes)): ?>
    <div class="alert alert-info">No hay clientes registrados.</div>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-bordered table-hover table-sm align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tipo Doc.</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($clientes as $c): ?>
            <tr>
                <td><?= $c['id_cliente'] ?></td>
                <td><?= htmlspecialchars($c['tp_doc']) ?></td>
                <td><?= htmlspecialchars($c['nombre']) ?></td>
                <td><?= htmlspecialchars($c['telefono']) ?></td>
                <td><?= htmlspecialchars($c['email']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
