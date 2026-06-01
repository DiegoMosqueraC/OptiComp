<?php
try {
    echo "--- INICIO DE COMUNICACION SOAP ---\n\n";

    // 1. DESCUBRIMIENTO (Consultar al UDDI)
    echo "1. Consultando al UDDI por 'TicketService'...\n";
    // Asegúrate de que la ruta coincida con la de tu Laragon
    $uddiUrl = "http://localhost/OptiComp-main/servidor_sockets/DirectorioUDDI.php?servicio=TicketService";
    
    $respuestaUDDI = file_get_contents($uddiUrl);
    $datosUDDI = json_decode($respuestaUDDI, true);

    if ($datosUDDI['status'] !== 'found') {
        throw new Exception("El servicio no existe en el UDDI.");
    }

    $wsdlUrl = $datosUDDI['wsdl_url'];
    echo "✔ UDDI respondió. Contrato WSDL encontrado en: $wsdlUrl\n\n";

    // 2. CONSUMO DEL SERVICIO (Conectar vía SOAP)
    echo "2. Conectando al Servidor SOAP...\n";
    $clienteSOAP = new SoapClient($wsdlUrl, ["cache_wsdl" => WSDL_CACHE_NONE]);

    // Preparamos los datos tal como exige el WSDL (TicketRequest)
    $parametros = [
        "ClienteId" => 102,
        "Equipo" => "PC Escritorio Dell",
        "Descripcion" => "La fuente de poder hizo cortocircuito",
        "FechaIngreso" => "2026-05-11"
    ];

    // Llamamos a la función remota ¡como si fuera local!
    $resultado = $clienteSOAP->crearTicket($parametros);

    // 3. RESULTADO
    echo "3. Respuesta oficial del Servidor SOAP:\n";
    echo "-> Estado: " . $resultado->Estado . "\n";
    echo "-> ID Ticket: " . $resultado->IdGenerado . "\n";
    echo "-> Detalle: " . $resultado->Mensaje . "\n\n";
    echo "-----------------------------------\n";

} catch (SoapFault $e) {
    echo "❌ Error SOAP: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error General: " . $e->getMessage() . "\n";
}
?>