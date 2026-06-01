<?php
<<<<<<< HEAD

// avance semana 5 cliente servidor|
=======
>>>>>>> 9a10ca8 (CODIGO UPDATE)
require_once __DIR__ . '/../app/Core/Ticket.php';
require_once __DIR__ . '/../app/Network/TicketDTO.php';
require_once __DIR__ . '/../app/Network/TicketStub.php';

try {
<<<<<<< HEAD


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
=======
    // 1. Preparamos el puerto por donde el Cliente escuchará el Callback
    $callbackIp = "127.0.0.1";
    $callbackPort = rand(7000, 7999); // Puerto aleatorio para no chocar
    
    // Creamos el socket de escucha del cliente ANTES de enviar
    $cbSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    socket_bind($cbSocket, $callbackIp, $callbackPort);
    socket_listen($cbSocket);

    // 2. Creamos el ticket
    $nuevoTicket = new Ticket(102, "Laptop HP Pavilion", "No enciende", "Abierto", "2026-02-15");

    // 3. Pasamos los datos al Stub.
    $stub = new TicketStub();
    
    echo "Enviando ticket...\n";
    $respuestaInicial = $stub->enviarTicket($nuevoTicket, $callbackIp, $callbackPort);
    
    // --- CAMBIO PARA LA GUÍA 8: IMPRESIÓN DEL XML ---
    echo "\n--- Respuesta Inmediata del Servidor (XML) ---\n";
    echo $respuestaInicial . "\n";
    echo "----------------------------------------------\n";

    echo "\n[Cliente] Esperando notificación asíncrona del servidor en el puerto $callbackPort...\n";
    
    // 4. EL CLIENTE ESPERA EL EVENTO DE NEGOCIO (CALLBACK)
    $serverConn = socket_accept($cbSocket);
    $notificacion = socket_read($serverConn, 1024);
    echo "\n🔔 ¡CALLBACK RECIBIDO! 🔔\nMensaje: " . $notificacion . "\n";
    
    socket_close($serverConn);
    socket_close($cbSocket);
>>>>>>> 9a10ca8 (CODIGO UPDATE)

} catch (Exception $e) {
    echo "Excepción: " . $e->getMessage() . "\n";
}
<<<<<<< HEAD
?>
=======
?>
>>>>>>> 9a10ca8 (CODIGO UPDATE)
