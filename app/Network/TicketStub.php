<?php
require_once __DIR__ . '/../Core/Ticket.php';
<<<<<<< HEAD
require_once __DIR__ . '/TicketDTO.php';

class TicketStub {
    // Arquitectura cliente servidor guia 6
    private $regHost = "127.0.0.1";
    private $regPort = 6000;

    public function enviarTicket(Ticket $ticket) {
        
=======

class TicketStub {
    private $regHost = "127.0.0.1";
    private $regPort = 6000;

    public function enviarTicket(Ticket $ticket, $cb_ip = null, $cb_port = null) {
        
        // 1. Buscar el servicio en el Registry (Esto lo dejamos en JSON porque es control interno)
>>>>>>> 9a10ca8 (CODIGO UPDATE)
        $regSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!@socket_connect($regSocket, $this->regHost, $this->regPort)) {
            throw new Exception("No se pudo conectar al Registry.");
        }
<<<<<<< HEAD
        
=======
>>>>>>> 9a10ca8 (CODIGO UPDATE)
        socket_write($regSocket, json_encode(["action" => "lookup", "service" => "TicketService"]));
        $res = json_decode(socket_read($regSocket, 1024), true);
        socket_close($regSocket);

        if ($res['status'] == "found") {
<<<<<<< HEAD
            
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

=======
            $ip = $res['data']['ip'];
            $port = $res['data']['port'];

            // --- ACTIVIDAD 3: CONSTRUCCIÓN DEL MENSAJE XML (Request) ---
            $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Mensaje></Mensaje>');
            $xml->addChild('Tipo', 'Request');
            $xml->addChild('Operacion', 'crear_ticket');
            
            $datos = $xml->addChild('Datos');
            $ticketNode = $datos->addChild('Ticket');
            $ticketNode->addChild('ClienteId', $ticket->getClienteId());
            $ticketNode->addChild('Equipo', htmlspecialchars($ticket->getEquipo()));
            $ticketNode->addChild('Descripcion', htmlspecialchars($ticket->getDescripcion()));
            $ticketNode->addChild('FechaIngreso', $ticket->getFechaIngreso());
            
            // Añadimos datos del callback si existen
            if ($cb_ip && $cb_port) {
                $cbNode = $datos->addChild('Callback');
                $cbNode->addChild('Ip', $cb_ip);
                $cbNode->addChild('Puerto', $cb_port);
            }

            // Generamos el string XML final
            $payload = $xml->asXML();

            // 2. Enviar el Payload XML al Servidor
>>>>>>> 9a10ca8 (CODIGO UPDATE)
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            socket_connect($socket, $ip, $port); 
            socket_write($socket, $payload, strlen($payload));
            
<<<<<<< HEAD
            $respuesta = socket_read($socket, 1024);
            socket_close($socket);
            
            return json_decode($respuesta, true);
=======
            // 3. Recibir la respuesta XML
            $respuestaXML = socket_read($socket, 2048);
            socket_close($socket);
            
            return $respuestaXML; // Devolvemos el XML crudo para que el Cliente.php lo vea
>>>>>>> 9a10ca8 (CODIGO UPDATE)

        } else {
            throw new Exception("Servicio 'TicketService' no encontrado.");
        }
    }
}
<<<<<<< HEAD
?>
=======
?>
>>>>>>> 9a10ca8 (CODIGO UPDATE)
