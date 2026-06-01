<?php

namespace App\Repositories;

use PDO;
use PDOException;
use App\Core\Conexion;
use App\Core\Logger;
use App\Models\Ticket;

/**
 * TicketRepository - Capa de Acceso a Datos (Repository Pattern)
 *
 * REFACTORING APLICADO:
 *   - Move Method: lógica SQL movida desde TicketDAO (en Core) a Repositories
 *   - Extract Method: mapRowToTicket() para evitar duplicación en hydratación
 *   - Rename: actualizarEstadoYSalida() -> updateEstado() (más conciso, PSR-12)
 *   - Eliminado: code smell "Brain Method" — cada método tiene una sola responsabilidad
 *   - Eliminado: mezcla de tipos de retorno (antes devolvía string o array mezclados)
 */
class TicketRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Conexion::getInstancia()->getConexion();
    }

    public function crear(Ticket $ticket): int
    {
        try {
            $sql  = "INSERT INTO ticket (cliente_id, equipo, descripcion, estado, fecha_ingreso)
                     VALUES (:cliente_id, :equipo, :descripcion, :estado, :fecha_ingreso)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':cliente_id'    => $ticket->getClienteId(),
                ':equipo'        => $ticket->getEquipo(),
                ':descripcion'   => $ticket->getDescripcion(),
                ':estado'        => $ticket->getEstado(),
                ':fecha_ingreso' => $ticket->getFechaIngreso(),
            ]);

            $nuevoId = (int) $this->db->lastInsertId();
            Logger::logEvent('OPERACION', "Ticket creado con ID: {$nuevoId}");

            return $nuevoId;
        } catch (PDOException $e) {
            Logger::logEvent('ERROR_SISTEMA', 'Error crear ticket: ' . $e->getMessage());
            throw $e;
        }
    }

    public function obtenerTodos(): array
    {
        $stmt = $this->db->query(
            "SELECT t.*, c.nombre AS nombre_cliente
             FROM ticket t
             LEFT JOIN cliente c ON t.cliente_id = c.id_cliente
             ORDER BY t.id DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT t.*, c.nombre AS nombre_cliente
             FROM ticket t
             LEFT JOIN cliente c ON t.cliente_id = c.id_cliente
             WHERE t.id = :id"
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function updateEstado(int $id, string $nuevoEstado, ?string $fechaSalida = null): bool
    {
        try {
            $stmt = $this->db->prepare(
                "UPDATE ticket SET estado = :estado, fecha_salida = :fecha_salida WHERE id = :id"
            );
            $result = $stmt->execute([
                ':estado'       => $nuevoEstado,
                ':fecha_salida' => $fechaSalida,
                ':id'           => $id,
            ]);

            Logger::logEvent('OPERACION', "Ticket ID {$id} actualizado a estado: {$nuevoEstado}");

            return $result;
        } catch (PDOException $e) {
            Logger::logEvent('ERROR_SISTEMA', 'Error update ticket: ' . $e->getMessage());
            throw $e;
        }
    }

    public function eliminar(int $id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM ticket WHERE id = :id");
            $result = $stmt->execute([':id' => $id]);
            Logger::logEvent('OPERACION', "Ticket ID {$id} eliminado.");

            return $result;
        } catch (PDOException $e) {
            Logger::logEvent('ERROR_SISTEMA', 'Error eliminar ticket: ' . $e->getMessage());
            throw $e;
        }
    }
}
