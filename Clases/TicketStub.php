<?php
require_once 'Ticket.php';

// Actividad 5 cliente servidor

class TicketStub {
    private $host;
    private $port;

    public function __construct($host = "127.0.0.1", $port = 5000) {
        $this->host = $host;
        $this->port = $port;
    }

  
    public function enviarTicket(Ticket $ticket) {

        $payload = json_encode($ticket);

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) {
            throw new Exception("Error al crear socket: " . socket_strerror(socket_last_error()));
        }

        $result = socket_connect($socket, $this->host, $this->port);
        if ($result === false) {
            throw new Exception("Error al conectar: " . socket_strerror(socket_last_error($socket)));
        }

        socket_write($socket, $payload, strlen($payload));

        $respuesta = socket_read($socket, 1024);
        
        socket_close($socket);
        
        return json_decode($respuesta, true);
    }
}
?>