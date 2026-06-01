<?php

/**
 * Router - Enrutador simple front-controller (MVC)
 * Archivo: routes/web.php
 */

use App\Controllers\TicketController;
use App\Controllers\ClienteController;
use App\Controllers\ProductoController;
use App\Controllers\ApiController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Quitar el base path si existe
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($basePath !== '' && str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath));
}

$uri = '/' . trim($uri, '/');

// ---- Rutas ----

// Home
if ($uri === '/' || $uri === '') {
    require __DIR__ . '/../app/Views/home.php';
    exit;
}

// Tickets
if ($uri === '/tickets') {
    (new TicketController())->index();
    exit;
}
if ($uri === '/tickets/crear') {
    (new TicketController())->crear();
    exit;
}
if (preg_match('#^/tickets/editar/(\d+)$#', $uri, $m)) {
    (new TicketController())->actualizar((int)$m[1]);
    exit;
}
if (preg_match('#^/tickets/eliminar/(\d+)$#', $uri, $m)) {
    (new TicketController())->eliminar((int)$m[1]);
    exit;
}

// Productos
if ($uri === '/productos') {
    (new ProductoController())->index();
    exit;
}
if ($uri === '/productos/crear') {
    (new ProductoController())->crear();
    exit;
}
if (preg_match('#^/productos/eliminar/(\d+)$#', $uri, $m)) {
    (new ProductoController())->eliminar((int)$m[1]);
    exit;
}

// Clientes
if ($uri === '/clientes') {
    (new ClienteController())->index();
    exit;
}
if ($uri === '/clientes/sincronizar') {
    (new ClienteController())->sincronizar();
    exit;
}

// API XML
if ($uri === '/api/xml') {
    (new ApiController())->xml();
    exit;
}

// 404
http_response_code(404);
echo '<div style="font-family:sans-serif;padding:2rem"><h2>404 — Página no encontrada</h2><a href="' . BASE_URL . '/">Volver al inicio</a></div>';
