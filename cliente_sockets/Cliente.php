<?php

// avance semana 5 cliente servidor|
require_once __DIR__ . '/../app/Core/Ticket.php';
require_once __DIR__ . '/../app/Network/TicketStub.php';

try {


    $nuevoTicket = new Ticket(
        102, 
        "Laptop HP Pavilion", 
        "No enciende", 
        "Abierto", // Estado inicial
        "2026-02-15"
    );


    $stub = new TicketStub();
    
    echo "Buscando servicio y enviando ticket al servidor...\n";
    

    $respuesta = $stub->enviarTicket($nuevoTicket);

    echo "Respuesta del servidor:\n";
    print_r($respuesta);

} catch (Exception $e) {
    echo "Excepción: " . $e->getMessage() . "\n";
}
?>
