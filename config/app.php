<?php

/**
 * Configuración general de la aplicación OptiComp
 */

return [
    'name'      => 'OptiComp',
    'version'   => '1.0.0',
    'env'       => 'development',
    'debug'     => true,
    'base_url'  => 'http://localhost/OptiComp/public',
    'log_path'  => __DIR__ . '/../logs/audit.log',
    'api' => [
        'external_users_url' => 'https://jsonplaceholder.typicode.com/users',
        'timeout'            => 10,
    ],
];
