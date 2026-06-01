<?php
header('Content-Type: application/json');

// Nuestra base de datos simulada de servicios registrados
$servicios_registrados = [
    // NOTA: Asegúrate de que "OptiComp-main" sea el nombre exacto de tu carpeta en Laragon
    "TicketService" => "http://localhost/OptiComp-main/servidor_sockets/TicketService.wsdl" 
];

$servicioSolicitado = $_GET['servicio'] ?? '';

if (array_key_exists($servicioSolicitado, $servicios_registrados)) {
    echo json_encode([
        "status" => "found", 
        "wsdl_url" => $servicios_registrados[$servicioSolicitado]
    ]);
} else {
    echo json_encode([
        "status" => "not_found", 
        "message" => "Servicio no registrado en el UDDI."
    ]);
}
?>