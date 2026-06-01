<?php

namespace App\Repositories;

use PDO;
use PDOException;
use App\Core\Conexion;
use App\Core\Logger;

/**
 * ClienteRepository - Acceso a datos de la entidad Cliente
 *
 * REFACTORING APLICADO:
 *   - Move Method: lógica SQL sacada de ServiceConnector y centralizada aquí
 *   - Extract Method: insertarDesdeApiExterna() separado del conector HTTP
 */
class ClienteRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Conexion::getInstancia()->getConexion();
    }

    public function obtenerTodos(): array
    {
        $stmt = $this->db->query("SELECT * FROM cliente ORDER BY id_cliente ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM cliente WHERE id_cliente = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * EXTRACT METHOD: inserción masiva desde datos externos (Web Service).
     * Antes estaba mezclada con la lógica HTTP de ServiceConnector.
     */
    public function insertarDesdeApiExterna(array $clientesExternos): int
    {
        $insertados = 0;

        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare(
                "INSERT IGNORE INTO cliente (id_cliente, tp_doc, nombre, telefono, email)
                 VALUES (:id, :tp_doc, :nombre, :telefono, :email)"
            );

            foreach ($clientesExternos as $cliente) {
                $stmt->execute([
                    ':id'       => $cliente['id'] + 100,
                    ':tp_doc'   => 'NIT',
                    ':nombre'   => $cliente['name'],
                    ':telefono' => substr($cliente['phone'], 0, 20),
                    ':email'    => $cliente['email'],
                ]);
                $insertados++;
            }

            $this->db->commit();
            Logger::logEvent('OPERACION', "Sincronización: {$insertados} clientes importados desde API externa.");
        } catch (PDOException $e) {
            $this->db->rollBack();
            Logger::logEvent('ERROR_SISTEMA', 'Error sync clientes: ' . $e->getMessage());
            throw $e;
        }

        return $insertados;
    }
}
