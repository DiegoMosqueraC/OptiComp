<?php

/**
 * Configuración de base de datos - OptiComp
 * Ambiente: Laragon / XAMPP (PHP 8.2, MySQL/MariaDB 10.4)
 */

return [
    'host'     => 'localhost',
    'dbname'   => 'db_opticomp',
    'username' => 'opticomp',  
    'password' => 'OptiComp2026!',
    'charset'  => 'utf8mb4',
    'options'  => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ],
];
