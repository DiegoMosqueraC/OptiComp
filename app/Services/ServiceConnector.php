<?php

namespace App\Services;

use App\Core\Logger;
use App\Repositories\ClienteRepository;

/**
 * ServiceConnector - Servicio de interoperabilidad con APIs externas (Guía 7)
 *
 * REFACTORING APLICADO:
 *   - Move Method: lógica de persistencia movida a ClienteRepository (donde corresponde)
 *   - Extract Method: fetchFromApi() separa la llamada HTTP de la lógica de negocio
 *   - Rename: sincronizarClientesExternos() mantiene nombre (ya era descriptivo)
 *   - Code smell corregido: antes tenía SQL directo — ahora delega al Repository
 */
class ServiceConnector
{
    private string $apiUrl;

    public function __construct()
    {
        $config       = require __DIR__ . '/../../config/app.php';
        $this->apiUrl = $config['api']['external_users_url'];
    }

    public function sincronizarClientesExternos(): array
    {
        $clientesExternos = $this->fetchFromApi($this->apiUrl);

        if ($clientesExternos === null) {
            return ['status' => 'error', 'message' => 'No se pudo conectar al servicio externo.'];
        }

        try {
            $repo       = new ClienteRepository();
            $insertados = $repo->insertarDesdeApiExterna($clientesExternos);

            return [
                'status'  => 'success',
                'message' => "Sincronización exitosa. {$insertados} clientes importados.",
                'data'    => $clientesExternos,
            ];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * EXTRACT METHOD: llamada HTTP con cURL encapsulada.
     * Antes estaba mezclada con la lógica de persistencia.
     */
    private function fetchFromApi(string $url): ?array
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_FOLLOWLOCATION => true,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_errno($ch) ? curl_error($ch) : null;
        curl_close($ch);

        if ($curlError) {
            Logger::logEvent('ERROR_SISTEMA', "cURL error al consumir API: {$curlError}");
            return null;
        }

        if ($httpCode !== 200) {
            Logger::logEvent('ERROR_SISTEMA', "API externa devolvió HTTP {$httpCode}");
            return null;
        }

        $decoded = json_decode($response, true);
        Logger::logEvent('OPERACION', "API externa consumida. Registros recibidos: " . count($decoded));

        return is_array($decoded) ? $decoded : null;
    }
}
