<?php

namespace App\Services;

use App\Core\Conexion;
use App\Core\Logger;

/**
 * ApiXmlService - Servicio de interoperabilidad XML (Guía 7 - API XML)
 *
 * REFACTORING APLICADO:
 *   - Move Method: lógica movida desde api/api_xml.php a Service layer
 *   - Code smell corregido: SQL con concatenación directa -> PDO con parámetros preparados
 *   - Code smell corregido: mysqli mezclado con PDO -> unificado en PDO
 *   - Extract Method: handleRegistrar() y handleConsultar() separados del switch principal
 */
class ApiXmlService
{
    public function procesarRequest(string $xmlInput): string
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlInput);

        if ($xml === false) {
            Logger::logEvent('ERROR_SISTEMA', 'XML inválido recibido en API.');
            return $this->buildResponse('ERROR', 'XML inválido o malformado');
        }

        $operacion = (string)($xml->operacion ?? '');
        Logger::logEvent('OPERACION', "API XML: operación recibida = {$operacion}");

        return match ($operacion) {
            'registrarProducto'  => $this->handleRegistrar($xml),
            'consultarProductos' => $this->handleConsultar(),
            default              => $this->buildResponse('ERROR', "Operación '{$operacion}' no reconocida"),
        };
    }

    /**
     * EXTRACT METHOD: manejo de registro de producto.
     */
    private function handleRegistrar(\SimpleXMLElement $xml): string
    {
        $descripcion  = htmlspecialchars((string)($xml->datos->nombre ?? ''), ENT_QUOTES, 'UTF-8');
        $categoriaId  = filter_var((string)($xml->datos->categoria_id ?? null), FILTER_VALIDATE_INT);

        if (empty($descripcion)) {
            return $this->buildResponse('ERROR', 'El nombre del producto es requerido.');
        }

        $db   = Conexion::getInstancia()->getConexion();
        $stmt = $db->prepare("INSERT INTO producto (descripcion, categoria_id) VALUES (:desc, :cat)");
        $stmt->execute([':desc' => $descripcion, ':cat' => $categoriaId ?: null]);

        $id = $db->lastInsertId();
        Logger::logEvent('OPERACION', "Producto registrado vía API XML. ID: {$id}");

        return $this->buildResponse('OK', "Producto registrado con ID: {$id}");
    }

    /**
     * EXTRACT METHOD: manejo de consulta de productos.
     */
    private function handleConsultar(): string
    {
        $db     = Conexion::getInstancia()->getConexion();
        $stmt   = $db->query("SELECT p.descripcion, c.detalle FROM producto p LEFT JOIN categoria c ON p.categoria_id = c.cod_categoria");
        $rows   = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $root = new \SimpleXMLElement('<response/>');
        $root->addChild('status', 'OK');
        $productos = $root->addChild('productos');

        foreach ($rows as $row) {
            $prod = $productos->addChild('producto');
            $prod->addChild('nombre', htmlspecialchars($row['descripcion']));
            $prod->addChild('categoria', htmlspecialchars($row['detalle'] ?? 'Sin categoría'));
        }

        return $root->asXML();
    }

    /**
     * EXTRACT METHOD: construcción uniforme de respuesta XML.
     */
    private function buildResponse(string $status, string $mensaje): string
    {
        $root = new \SimpleXMLElement('<response/>');
        $root->addChild('status', $status);
        $root->addChild('mensaje', htmlspecialchars($mensaje));
        return $root->asXML();
    }
}
