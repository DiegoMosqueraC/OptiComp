<?php

/**
 * OptiComp - Front Controller
 * Punto de entrada único del sistema MVC
 *
 * Coloque este archivo en la carpeta /public/ del servidor.
 * Laragon: http://localhost/OptiComp/public/
 */

define('BASE_PATH', dirname(__DIR__));
define(
    'BASE_URL',
    (isset($_SERVER['HTTPS']) ? 'https' : 'http')
    . '://'
    . $_SERVER['HTTP_HOST']
);

// ---- Autoload de namespaces ----
spl_autoload_register(function (string $class): void {
    // Convierte App\Controllers\TicketController -> /app/Controllers/TicketController.php
    $prefix   = 'App\\';
    $baseDir  = BASE_PATH . '/app/';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $file     = $baseDir . str_replace('\\', '/', $relative) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// ---- Manejo global de errores ----
set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline): bool {
    if (!(error_reporting() & $errno)) {
        return false;
    }
    error_log("[OptiComp] Error PHP: {$errstr} en {$errfile}:{$errline}");
    return true;
});

// ---- Enrutamiento ----
require BASE_PATH . '/routes/web.php';
