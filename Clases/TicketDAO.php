<?php

require_once __DIR__ . '/../Core/Conexion.php';
require_once __DIR__ . '/../Core/Ticket.php';

class TicketDAO {
    
    private $conexion;

    public function __construct() {
        // Usamos el Singleton de la Guía 5
        $this->conexion = Conexion::getInstancia()->getConexion();
    }

    // CREATE
    public function crear(Ticket $ticket) {
        try {
            $sql = "INSERT INTO ticket (cliente_id, equipo, descripcion, estado, fecha_ingreso) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([
                $ticket->getClienteId(),
                $ticket->getEquipo(),
                $ticket->getDescripcion(),
                $ticket->getEstado(),
                $ticket->getFechaIngreso()
            ]);
            return "✔ Ticket insertado correctamente con ID: " . $this->conexion->lastInsertId();
        } catch (PDOException $e) {
            return "❌ Error al insertar ticket: " . $e->getMessage();
        }
    }

    // READ
    public function obtenerTodos() {
        try {
            $stmt = $this->conexion->query("SELECT * FROM ticket"); // Corregido de "tickets" a "ticket"
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "❌ Error de lectura: " . $e->getMessage();
        }
    }

    // UPDATE
    public function actualizarEstado($id, $nuevoEstado) {
        try {
            $sql = "UPDATE ticket SET estado = ? WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$nuevoEstado, $id]);
            return "✔ Estado actualizado.";
        } catch (PDOException $e) {
            return "❌ Error al actualizar: " . $e->getMessage();
        }
    }

    // DELETE
    public function eliminar($id) {
        try {
            $sql = "DELETE FROM ticket WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id]);
            return "✔ Ticket eliminado.";
        } catch (PDOException $e) {
            return "❌ Error al eliminar: " . $e->getMessage();
        }
    }
}
?>
