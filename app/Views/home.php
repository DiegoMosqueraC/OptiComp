<?php $pageTitle = 'Inicio'; ?>
<?php require __DIR__ . '/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col">
        <h4 class="mb-1">Panel de Control</h4>
        <p class="text-muted mb-0">Sistema de gestión de tickets y componentes — OptiComp</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-start border-primary border-4 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-ticket-detailed fs-2 text-primary"></i>
                <div>
                    <div class="fw-semibold">Tickets de Soporte</div>
                    <a href="<?= BASE_URL ?>/tickets" class="btn btn-sm btn-outline-primary mt-1">Ver tickets</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-start border-success border-4 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-box-seam fs-2 text-success"></i>
                <div>
                    <div class="fw-semibold">Catálogo de Productos</div>
                    <a href="<?= BASE_URL ?>/productos" class="btn btn-sm btn-outline-success mt-1">Ver productos</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-start border-secondary border-4 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-people fs-2 text-secondary"></i>
                <div>
                    <div class="fw-semibold">Clientes</div>
                    <a href="<?= BASE_URL ?>/clientes" class="btn btn-sm btn-outline-secondary mt-1">Ver clientes</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-light">
        <strong><i class="bi bi-info-circle me-1"></i>Información del Sistema</strong>
    </div>
    <div class="card-body">
        <table class="table table-sm table-borderless mb-0">
            <tbody>
                <tr><td class="text-muted" style="width:180px">Proyecto</td><td><strong>OptiComp</strong></td></tr>
                <tr><td class="text-muted">Versión</td><td>1.0.0</td></tr>
                <tr><td class="text-muted">Arquitectura</td><td>MVC (PHP 8.2 + PDO + Bootstrap 5)</td></tr>
                <tr><td class="text-muted">Asignatura</td><td>Arquitectura y Diseño de Software — FESC</td></tr>
                <tr><td class="text-muted">Entorno</td><td>Laragon / XAMPP</td></tr>
                <tr><td class="text-muted">Base de datos</td><td>MySQL / MariaDB — <code>db_opticomp</code></td></tr>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>
