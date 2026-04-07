<?php

// avance semana 5 cliente servidor|

require_once 'clases/TicketDTO.php';
require_once 'clases/TicketStub.php';

try {

    $nuevoTicket = new Ticket(
        "crear_ticket", 
        102, 
        "Laptop HP Pavilion", 
        "No enciende", 
        "2026-02-15"
    );

    $stub = new TicketStub("127.0.0.1", 5005);
    
    echo "Enviando ticket al servidor...\n";
    $respuesta = $stub->enviarTicket($nuevoTicket);

    echo "Respuesta del servidor:\n";
    print_r($respuesta);

} catch (Exception $e) {
    echo "Excepción: " . $e->getMessage() . "\n";
}
?>
