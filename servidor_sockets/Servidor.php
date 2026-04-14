<?php
// avance semana 5 y 6 cliente servidor
require_once __DIR__ . '/../app/Core/Ticket.php';
require_once __DIR__ . '/../app/Core/TicketDAO.php';

$host = "127.0.0.1";
$port = 5005; 


$registryHost = "127.0.0.1";
$registryPort = 6000; 

$regSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if (@socket_connect($regSocket, $registryHost, $registryPort)) {
    $bindRequest = json_encode([
        "action" => "bind",
        "service" => "TicketService",
        "ip" => $host,
        "port" => $port
    ]);
    socket_write($regSocket, $bindRequest, strlen($bindRequest));
    socket_close($regSocket);
    echo "[G6] Bind exitoso: Servidor registrado como 'TicketService'\n";
} else {
    echo "[ALERTA] No se pudo conectar al Registry. Asegúrate de que Registry.php esté corriendo.\n";
}


$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($socket, $host, $port);
socket_listen($socket);

echo "Servidor de Soporte Técnico escuchando en $host:$port...\n";

while (true) {
    $client = socket_accept($socket);
    $payload = socket_read($client, 1024);
    
    if ($payload) {
        echo "\n--- [G5] Nuevo Payload Recibido (Marshaling) ---\n";
        echo "Bytes crudos: " . $payload . "\n";
        
        $datosDecodificados = json_decode($payload, true);
        
 
        $ticketReconstruido = new Ticket(
            $datosDecodificados['cliente_id'],
            $datosDecodificados['equipo'],
            $datosDecodificados['descripcion'],
            "Abierto", // Estado inicial por defecto
            $datosDecodificados['fecha_ingreso']
        );
        
        echo "Objeto reconstruido exitosamente (Unmarshaling):\n";
        var_dump($ticketReconstruido);

    
        $respuesta = json_encode([
            "status" => "success",
            "mensaje" => "✔ Ticket registrado dinámicamente vía Registry"
        ]);

        socket_write($client, $respuesta, strlen($respuesta));
    }
    socket_close($client);
}
socket_close($socket);
?>
