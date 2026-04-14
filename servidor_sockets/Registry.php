<?php
// Arquitectura cliente-servidor guia 6
$host = "127.0.0.1";
$port = 6000; 
$servicios = []; 

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($socket, $host, $port);
socket_listen($socket);

echo "Servicio de Naming (Registry) en el puerto $port...\n";

while (true) {
    $client = socket_accept($socket);
    $request = json_decode(socket_read($client, 1024), true);

    if ($request) {
        if ($request['action'] == "bind") {
            $servicios[$request['service']] = ["ip" => $request['ip'], "port" => $request['port']];
            socket_write($client, json_encode(["status" => "ok"]));
            echo "Servicio registrado: " . $request['service'] . "\n";
        } elseif ($request['action'] == "lookup") {
            $res = isset($servicios[$request['service']]) ? ["status" => "found", "data" => $servicios[$request['service']]] : ["status" => "not_found"];
            socket_write($client, json_encode($res));
        }
    }
    socket_close($client);
}
