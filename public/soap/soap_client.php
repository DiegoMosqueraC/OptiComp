<?php

/**
 * OptiComp — Cliente SOAP (Actividad 3 - Guía 10)
 *
 * Consume los métodos remotos publicados por server.php.
 * Demuestra: serialización SOAP, descubrimiento dinámico via UDDI-like,
 * y verificación de integración del sistema distribuido.
 *
 * Ejecución desde terminal:
 *   php soap_client.php
 *
 * Ejecución desde navegador:
 *   http://<IP>/OptiComp/public/soap/soap_client.php
 */

// ── Descubrimiento dinámico del endpoint (simula UDDI) ──────────────
// En lugar de IP fija, se consulta el registro UDDI-like local.
// Si no existe, usa la IP del servidor por defecto.
$uddiRegistryPath = __DIR__ . '/uddi_registry.json';

if (file_exists($uddiRegistryPath)) {
    $registry    = json_decode(file_get_contents($uddiRegistryPath), true);
    $wsdlUrl     = $registry['services']['OptiCompService']['wsdl_url'] ?? null;
    $endpointUrl = $registry['services']['OptiCompService']['endpoint_url'] ?? null;
} else {
    // Fallback: construir URL dinámica desde la petición actual
    $host        = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $dir         = dirname($_SERVER['SCRIPT_NAME'] ?? '/public/soap/soap_client.php');
    $wsdlUrl     = "http://{$host}{$dir}/server.php?wsdl";
    $endpointUrl = "http://{$host}{$dir}/server.php";
}

// ── Inicializar cliente SOAP ─────────────────────────────────────────
$isCli = php_sapi_name() === 'cli';

if (!$isCli) {
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8">
    <title>OptiComp - Cliente SOAP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    </head><body><div class="container my-4">
    <h3 class="mb-1"><strong>OptiComp</strong> — Cliente SOAP (Guía 10)</h3>
    <p class="text-muted mb-4">Consumo de métodos remotos desde nodo cliente</p>';
}

$resultados = [];
$errores    = [];

try {
    $client = new SoapClient($wsdlUrl, [
        'trace'              => true,
        'exceptions'         => true,
        'connection_timeout' => 10,
        'encoding'           => 'UTF-8',
    ]);

    // ── TEST 1: pingServidor ─────────────────────────────────────────
    $ping = $client->pingServidor();
    $resultados['ping'] = [
        'metodo'    => 'pingServidor()',
        'resultado' => $ping,
        'soap_out'  => $client->__getLastRequest(),
        'soap_in'   => $client->__getLastResponse(),
    ];

    // ── TEST 2: consultarProductos ───────────────────────────────────
    $productos = $client->consultarProductos();
    $resultados['productos'] = [
        'metodo'    => 'consultarProductos()',
        'resultado' => $productos,
        'soap_out'  => $client->__getLastRequest(),
        'soap_in'   => $client->__getLastResponse(),
    ];

    // ── TEST 3: registrarProducto ────────────────────────────────────
    $nuevo = $client->registrarProducto('Prueba SOAP - DDR5 32GB', 2);
    $resultados['registrar_producto'] = [
        'metodo'    => "registrarProducto('Prueba SOAP - DDR5 32GB', 2)",
        'resultado' => $nuevo,
        'soap_out'  => $client->__getLastRequest(),
        'soap_in'   => $client->__getLastResponse(),
    ];

    // ── TEST 4: consultarTickets ─────────────────────────────────────
    $tickets = $client->consultarTickets();
    $resultados['tickets'] = [
        'metodo'    => 'consultarTickets()',
        'resultado' => $tickets,
        'soap_out'  => $client->__getLastRequest(),
        'soap_in'   => $client->__getLastResponse(),
    ];

    // ── TEST 5: registrarTicket ──────────────────────────────────────
    $nuevoTicket = $client->registrarTicket(1, 'PC Ensamble SOAP', 'Falla de RAM detectada vía método remoto', 'Abierto');
    $resultados['registrar_ticket'] = [
        'metodo'    => "registrarTicket(1, 'PC Ensamble SOAP', '...', 'Abierto')",
        'resultado' => $nuevoTicket,
        'soap_out'  => $client->__getLastRequest(),
        'soap_in'   => $client->__getLastResponse(),
    ];

} catch (SoapFault $e) {
    $errores[] = "SoapFault [{$e->faultcode}]: {$e->faultstring}";
} catch (Exception $e) {
    $errores[] = "Error general: " . $e->getMessage();
}

// ── Renderizar resultados ────────────────────────────────────────────
if ($isCli) {
    // Salida de consola (para Ubuntu Server)
    echo "\n========================================\n";
    echo "  OptiComp — Cliente SOAP (Guia 10)\n";
    echo "  WSDL: {$wsdlUrl}\n";
    echo "========================================\n\n";

    foreach ($resultados as $key => $r) {
        echo ">>> {$r['metodo']}\n";
        echo "    Resultado: " . json_encode($r['resultado'], JSON_UNESCAPED_UNICODE) . "\n\n";
        echo "    -- Request SOAP --\n";
        echo "    " . str_replace("\n", "\n    ", trim($r['soap_out'])) . "\n\n";
        echo "    -- Response SOAP --\n";
        echo "    " . str_replace("\n", "\n    ", trim($r['soap_in'])) . "\n\n";
        echo str_repeat('-', 60) . "\n\n";
    }

    if (!empty($errores)) {
        echo "ERRORES:\n";
        foreach ($errores as $err) {
            echo "  [!] {$err}\n";
        }
    }

    echo "Prueba finalizada.\n";

} else {
    // Salida HTML (para navegador)
    if (!empty($errores)) {
        foreach ($errores as $err) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($err) . '</div>';
        }
    }

    echo '<div class="mb-3"><strong>WSDL consumido:</strong> <code>' . htmlspecialchars($wsdlUrl) . '</code></div>';

    foreach ($resultados as $r) {
        echo '<div class="card mb-3">';
        echo '<div class="card-header bg-dark text-white"><code>' . htmlspecialchars($r['metodo']) . '</code></div>';
        echo '<div class="card-body">';

        // Resultado
        echo '<h6>Resultado:</h6>';
        echo '<pre class="bg-light p-2 rounded" style="font-size:.8rem">'
            . htmlspecialchars(json_encode($r['resultado'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))
            . '</pre>';

        // Envelope SOAP enviado
        echo '<h6>Envelope SOAP enviado (Request):</h6>';
        echo '<pre class="bg-light p-2 rounded" style="font-size:.75rem;max-height:150px;overflow:auto">'
            . htmlspecialchars($r['soap_out'])
            . '</pre>';

        // Envelope SOAP recibido
        echo '<h6>Envelope SOAP recibido (Response):</h6>';
        echo '<pre class="bg-light p-2 rounded" style="font-size:.75rem;max-height:150px;overflow:auto">'
            . htmlspecialchars($r['soap_in'])
            . '</pre>';

        echo '</div></div>';
    }

    echo '</div></body></html>';
}
