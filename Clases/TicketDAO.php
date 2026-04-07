<?php

require_once __DIR__ . '/../Core/Conexion.php';
require_once __DIR__ . '/../Core/Ticket.php';

class TicketDAO {

    public function crear($ticket) {

        $conexion = Conexion::getInstancia()->getConexion();

        $sql = "INSERT INTO tickets (cliente_id, equipo, descripcion, estado, fecha_ingreso)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            $ticket->getClienteId(),
            $ticket->getEquipo(),
            $ticket->getDescripcion(),
            $ticket->getEstado(),
            $ticket->getFechaIngreso()
        ]);

        return "✔ Ticket insertado correctamente";
    }

    public function obtenerTodos() {
        $conexion = Conexion::getInstancia()->getConexion();
        return $conexion->query("SELECT * FROM tickets");
    }
}
?>
