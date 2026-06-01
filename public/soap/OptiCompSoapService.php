<?php

/**
 * OptiCompSoapService — Librería de métodos remotos (Guía 10 - Actividad 2)
 *
 * Esta clase expone los métodos del MVP como operaciones SOAP.
 * Cada método público aquí se convierte en un método remoto invocable.
 */
class OptiCompSoapService
{
    // ----------------------------------------------------------------
    // MÉTODO: pingServidor
    // Propósito: verificar que el servidor responde (prueba de conectividad)
    // ----------------------------------------------------------------
    public function pingServidor(): string
    {
        return 'OptiComp SOAP Server OK — ' . date('Y-m-d H:i:s');
    }

    // ----------------------------------------------------------------
    // MÉTODO: consultarProductos
    // Propósito: retornar lista de productos del catálogo
    // ----------------------------------------------------------------
    public function consultarProductos(): array
    {
        try {
            $db   = \App\Core\Conexion::getInstancia()->getConexion();
            $stmt = $db->query(
                "SELECT p.id, p.descripcion, COALESCE(c.detalle, 'Sin categoría') AS categoria
                 FROM producto p
                 LEFT JOIN categoria c ON p.categoria_id = c.cod_categoria
                 ORDER BY p.id ASC"
            );
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            \App\Core\Logger::logEvent('SOAP', 'consultarProductos: ' . count($rows) . ' registros retornados.');
            return $rows;

        } catch (\Exception $e) {
            \App\Core\Logger::logEvent('ERROR_SOAP', 'consultarProductos: ' . $e->getMessage());
            throw new SoapFault('Server', 'Error al consultar productos: ' . $e->getMessage());
        }
    }

    // ----------------------------------------------------------------
    // MÉTODO: registrarProducto
    // Propósito: insertar un nuevo producto vía llamada SOAP remota
    // ----------------------------------------------------------------
    public function registrarProducto(string $descripcion, int $categoria_id): array
    {
        if (empty(trim($descripcion))) {
            throw new SoapFault('Client', 'La descripción del producto no puede estar vacía.');
        }

        try {
            $db   = \App\Core\Conexion::getInstancia()->getConexion();
            $stmt = $db->prepare(
                "INSERT INTO producto (descripcion, categoria_id) VALUES (:desc, :cat)"
            );
            $stmt->execute([
                ':desc' => htmlspecialchars(trim($descripcion), ENT_QUOTES, 'UTF-8'),
                ':cat'  => $categoria_id > 0 ? $categoria_id : null,
            ]);
            $id = (int) $db->lastInsertId();

            \App\Core\Logger::logEvent('SOAP', "registrarProducto: ID {$id} insertado vía SOAP.");

            return [
                'status'  => 'OK',
                'mensaje' => "Producto registrado correctamente.",
                'id'      => $id,
            ];

        } catch (\Exception $e) {
            \App\Core\Logger::logEvent('ERROR_SOAP', 'registrarProducto: ' . $e->getMessage());
            throw new SoapFault('Server', 'Error al registrar producto: ' . $e->getMessage());
        }
    }

    // ----------------------------------------------------------------
    // MÉTODO: consultarTickets
    // Propósito: retornar lista de tickets de soporte activos
    // ----------------------------------------------------------------
    public function consultarTickets(): array
    {
        try {
            $db   = \App\Core\Conexion::getInstancia()->getConexion();
            $stmt = $db->query(
                "SELECT t.id, t.equipo, t.descripcion, t.estado,
                        t.fecha_ingreso, COALESCE(c.nombre, 'Sin cliente') AS nombre_cliente
                 FROM ticket t
                 LEFT JOIN cliente c ON t.cliente_id = c.id_cliente
                 ORDER BY t.id DESC"
            );
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            \App\Core\Logger::logEvent('SOAP', 'consultarTickets: ' . count($rows) . ' tickets retornados.');
            return $rows;

        } catch (\Exception $e) {
            \App\Core\Logger::logEvent('ERROR_SOAP', 'consultarTickets: ' . $e->getMessage());
            throw new SoapFault('Server', 'Error al consultar tickets: ' . $e->getMessage());
        }
    }

    // ----------------------------------------------------------------
    // MÉTODO: registrarTicket
    // Propósito: crear un nuevo ticket de soporte vía SOAP
    // ----------------------------------------------------------------
    public function registrarTicket(
        int    $cliente_id,
        string $equipo,
        string $descripcion,
        string $estado
    ): array {
        if (empty(trim($equipo)) || empty(trim($descripcion))) {
            throw new SoapFault('Client', 'El equipo y la descripción son campos obligatorios.');
        }

        $estadosValidos = ['Abierto', 'En proceso', 'Cerrado'];
        if (!in_array($estado, $estadosValidos, true)) {
            throw new SoapFault('Client', 'Estado inválido. Use: Abierto, En proceso o Cerrado.');
        }

        try {
            $db   = \App\Core\Conexion::getInstancia()->getConexion();
            $stmt = $db->prepare(
                "INSERT INTO ticket (cliente_id, equipo, descripcion, estado, fecha_ingreso)
                 VALUES (:cid, :equipo, :desc, :estado, :fecha)"
            );
            $stmt->execute([
                ':cid'    => $cliente_id,
                ':equipo' => htmlspecialchars(trim($equipo), ENT_QUOTES, 'UTF-8'),
                ':desc'   => htmlspecialchars(trim($descripcion), ENT_QUOTES, 'UTF-8'),
                ':estado' => $estado,
                ':fecha'  => date('Y-m-d'),
            ]);
            $id = (int) $db->lastInsertId();

            \App\Core\Logger::logEvent('SOAP', "registrarTicket: Ticket #{$id} creado vía SOAP.");

            return [
                'status'  => 'OK',
                'mensaje' => "Ticket registrado correctamente.",
                'id'      => $id,
            ];

        } catch (\Exception $e) {
            \App\Core\Logger::logEvent('ERROR_SOAP', 'registrarTicket: ' . $e->getMessage());
            throw new SoapFault('Server', 'Error al registrar ticket: ' . $e->getMessage());
        }
    }
}
