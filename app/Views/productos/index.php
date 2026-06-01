<?php $pageTitle = 'Catálogo de Productos'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-box-seam me-2"></i>Catálogo de Componentes PC</h4>
    <a href="<?= BASE_URL ?>/productos/crear" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i>Agregar Producto
    </a>
</div>

<?php if (empty($productos)): ?>
    <div class="alert alert-info">No hay productos registrados. Agregue el primero.</div>
<?php else: ?>
<div class="row row-cols-1 row-cols-md-3 g-3">
    <?php foreach ($productos as $p): ?>
    <div class="col">
        <div class="card h-100 border">
            <div class="card-body">
                <h6 class="card-title"><?= htmlspecialchars($p['descripcion']) ?></h6>
                <p class="card-text text-muted small mb-0">
                    <i class="bi bi-tag me-1"></i><?= htmlspecialchars($p['categoria_nombre'] ?? 'Sin categoría') ?>
                </p>
            </div>
            <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                <small class="text-muted">ID: <?= $p['id'] ?></small>
                <a href="<?= BASE_URL ?>/productos/eliminar/<?= $p['id'] ?>"
                   class="btn btn-outline-danger btn-sm"
                   onclick="return confirm('¿Eliminar este producto?')">
                    <i class="bi bi-trash"></i>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
