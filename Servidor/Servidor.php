<?php

// avance semana 5 cliente servidor

require_once __DIR__ . '/../Clases/Ticket.php';
$host = "127.0.0.1";
$port = 5005;

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($socket, $host, $port);
socket_listen($socket);

echo "Servidor de Soporte Técnico escuchando en $host:$port...\n";

while (true) {
    $client = socket_accept($socket);
    
    $payload = socket_read($client, 1024);
    
    if ($payload) {
        echo "\n--- Nuevo Payload Recibido ---\n";
        echo "Bytes crudos: " . $payload . "\n";
        
        $datosDecodificados = json_decode($payload, true);
        
        $ticketReconstruido = new Ticket(
            $datosDecodificados['accion'],
            $datosDecodificados['cliente_id'],
            $datosDecodificados['equipo'],
            $datosDecodificados['descripcion_problema'],
            $datosDecodificados['fecha_ingreso']
        );
        
        echo "Objeto reconstruido exitosamente. Estado original:\n";
        var_dump($ticketReconstruido);

        $respuesta = json_encode([
            "status" => "success",
            "ticket_id" => rand(100, 999), // ID simulado
            "mensaje" => "Ticket registrado correctamente"
        ]);

        socket_write($client, $respuesta, strlen($respuesta));
    }
    
    socket_close($client);
}

socket_close($socket);
?>