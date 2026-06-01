<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'OptiComp' ?> | OptiComp</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/app.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/">
            <i class="bi bi-cpu-fill me-1"></i> OptiComp
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/tickets') ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>/tickets">
                        <i class="bi bi-ticket-detailed me-1"></i>Tickets
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/productos') ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>/productos">
                        <i class="bi bi-box-seam me-1"></i>Productos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/clientes') ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>/clientes">
                        <i class="bi bi-people me-1"></i>Clientes
                    </a>
                </li>
            </ul>
            <span class="navbar-text text-secondary small">
                Arquitectura &amp; Diseño de Software — FESC
            </span>
        </div>
    </div>
</nav>

<main class="container my-4">
