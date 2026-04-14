<?php
require_once 'Ticket.php';
require_once 'TicketDTO.php'; // <-- 1. Importamos el DTO

class TicketStub {
    // Arquitectura cliente servidor guia 6
    private $regHost = "127.0.0.1";
    private $regPort = 6000;

    public function enviarTicket(Ticket $ticket) {
        
        $regSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!@socket_connect($regSocket, $this->regHost, $this->regPort)) {
            throw new Exception("No se pudo conectar al Registry.");
        }
        
        socket_write($regSocket, json_encode(["action" => "lookup", "service" => "TicketService"]));
        $res = json_decode(socket_read($regSocket, 1024), true);
        socket_close($regSocket);

        if ($res['status'] == "found") {
            
            $ip = $res['data']['ip'];
            $port = $res['data']['port'];

            // --- 2. SOLUCIÓN AL PROBLEMA DE ENCAPSULAMIENTO ---
            // Mapeamos la Entidad (privada) hacia el DTO (público) usando los Getters
            $ticketDTO = new TicketDTO(
                "crear_ticket", // O la acción que requieras en tu servidor
                $ticket->getClienteId(),
                $ticket->getEquipo(),
                $ticket->getDescripcion(),
                $ticket->getFechaIngreso()
            );

            // 3. Serializamos el DTO, no la Entidad
            $payload = json_encode($ticketDTO); 

            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            socket_connect($socket, $ip, $port); 
            socket_write($socket, $payload, strlen($payload));
            
            $respuesta = socket_read($socket, 1024);
            socket_close($socket);
            
            return json_decode($respuesta, true);

        } else {
            throw new Exception("Servicio 'TicketService' no encontrado.");
        }
    }
}
?>
