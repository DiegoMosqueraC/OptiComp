<?php

namespace App\Controllers;

use App\Models\Ticket;
use App\Repositories\TicketRepository;
use App\Helpers\Validador;
use App\Core\Logger;

/**
 * TicketController - Controlador MVC para gestión de tickets de soporte
 */
class TicketController
{
    private TicketRepository $repo;

    public function __construct()
    {
        $this->repo = new TicketRepository();
    }

    public function index(): void
    {
        $tickets = $this->repo->obtenerTodos();
        require __DIR__ . '/../Views/tickets/index.php';
    }

    public function crear(): void
    {
        $errores = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errores = Validador::validateTicketForm($_POST);
            if (empty($errores)) {
                $ticket = new Ticket(
                    (int)$_POST['cliente_id'],
                    Validador::texto($_POST['equipo']),
                    Validador::texto($_POST['descripcion']),
                    Validador::texto($_POST['estado']),
                    date('Y-m-d')
                );
                $id = $this->repo->crear($ticket);
                Logger::logEvent('OPERACION', "Ticket #{$id} creado vía formulario web.");
                header('Location: ' . BASE_URL . '/tickets');
                exit;
            }
        }
        require __DIR__ . '/../Views/tickets/crear.php';
    }

    public function actualizar(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $estado      = Validador::texto($_POST['estado'] ?? '');
            $fechaSalida = !empty($_POST['fecha_salida']) ? $_POST['fecha_salida'] : null;
            $this->repo->updateEstado($id, $estado, $fechaSalida);
            header('Location: ' . BASE_URL . '/tickets');
            exit;
        }
        $ticket = $this->repo->obtenerPorId($id);
        require __DIR__ . '/../Views/tickets/editar.php';
    }

    public function eliminar(int $id): void
    {
        $this->repo->eliminar($id);
        header('Location: ' . BASE_URL . '/tickets');
        exit;
    }
}
