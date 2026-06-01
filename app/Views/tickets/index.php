<?php $pageTitle = 'Tickets de Soporte'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-ticket-detailed me-2"></i>Tickets de Soporte</h4>
    <a href="<?= BASE_URL ?>/tickets/crear" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i>Nuevo Ticket
    </a>
</div>

<?php if (empty($tickets)): ?>
    <div class="alert alert-info">No hay tickets registrados aún.</div>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-bordered table-hover table-sm align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Equipo</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Ingreso</th>
                <th>Salida</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($tickets as $t): ?>
            <tr>
                <td><?= $t['id'] ?></td>
                <td><?= htmlspecialchars($t['nombre_cliente'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($t['equipo']) ?></td>
                <td class="text-truncate" style="max-width:200px"><?= htmlspecialchars($t['descripcion']) ?></td>
                <td>
                    <?php
                    $badge = match($t['estado']) {
                        'Abierto'    => 'bg-success',
                        'En proceso' => 'bg-warning text-dark',
                        'Cerrado'    => 'bg-secondary',
                        default      => 'bg-light text-dark',
                    };
                    ?>
                    <span class="badge <?= $badge ?>"><?= htmlspecialchars($t['estado']) ?></span>
                </td>
                <td><?= $t['fecha_ingreso'] ?></td>
                <td><?= $t['fecha_salida'] ?? '—' ?></td>
                <td class="text-center">
                    <a href="<?= BASE_URL ?>/tickets/editar/<?= $t['id'] ?>"
                       class="btn btn-outline-secondary btn-sm" title="Editar">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <a href="<?= BASE_URL ?>/tickets/eliminar/<?= $t['id'] ?>"
                       class="btn btn-outline-danger btn-sm"
                       onclick="return confirm('¿Eliminar ticket #<?= $t['id'] ?>?')" title="Eliminar">
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
