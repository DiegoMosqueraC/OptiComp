<?php

namespace App\Controllers;

use App\Repositories\ClienteRepository;
use App\Services\ServiceConnector;
use App\Helpers\Validador;
use App\Core\Logger;

class ClienteController
{
    private ClienteRepository $repo;

    public function __construct()
    {
        $this->repo = new ClienteRepository();
    }

    public function index(): void
    {
        $clientes = $this->repo->obtenerTodos();
        require __DIR__ . '/../Views/clientes/index.php';
    }

    public function sincronizar(): void
    {
        $servicio  = new ServiceConnector();
        $resultado = $servicio->sincronizarClientesExternos();
        $mensaje   = $resultado['message'];
        $tipo      = $resultado['status'];
        $clientes  = $this->repo->obtenerTodos();
        require __DIR__ . '/../Views/clientes/index.php';
    }
}
