<?php
<<<<<<< HEAD
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
=======
require_once __DIR__ . '/../app/Core/Ticket.php';

$host = "127.0.0.1";
$port = 5005; 
$registryHost = "127.0.0.1";
$registryPort = 6000; 

// Registro en el Registry
$regSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if (@socket_connect($regSocket, $registryHost, $registryPort)) {
    $bindRequest = json_encode(["action" => "bind", "service" => "TicketService", "ip" => $host, "port" => $port]);
    socket_write($regSocket, $bindRequest, strlen($bindRequest));
    socket_close($regSocket);
    echo "[G8] Bind exitoso: Servidor XML registrado.\n";
}

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($socket, $host, $port);
socket_listen($socket);
echo "Servidor XML escuchando en $host:$port...\n";

while (true) {
    $client = socket_accept($socket);
    $payload = socket_read($client, 2048);
    
    if ($payload) {
        echo "\n--- [G8] Nuevo XML Recibido ---\n";
        
        $doc = new DOMDocument();
        // Evitamos warnings si el XML viene mal formado inicialmente
        libxml_use_internal_errors(true); 
        $doc->loadXML($payload);

        // --- ACTIVIDAD 2: VALIDACIÓN CON XSD ---
        if (!$doc->schemaValidate('protocolo.xsd')) {
            echo "❌ Error: El XML no cumple con el protocolo XSD.\n";
            $respuestaXML = '<?xml version="1.0" encoding="UTF-8"?><Mensaje><Tipo>Error</Tipo><Estado>Fallo</Estado><Control><Codigo>400</Codigo><Detalle>Estructura XML Invalida</Detalle></Control></Mensaje>';
            socket_write($client, $respuestaXML, strlen($respuestaXML));
            socket_close($client);
            continue;
        }

        echo "✔ XML Válido según protocolo.xsd\n";

        // --- ACTIVIDAD 4: EXTRACCIÓN CON XPATH ---
        $xpath = new DOMXPath($doc);
        
        // Buscamos nodos específicos navegando la ruta
        $operacion = $xpath->query("//Operacion")->item(0)->nodeValue;
        
        if ($operacion == 'crear_ticket') {
            // Extraemos los datos del ticket usando XPath
            $clienteId = $xpath->query("//Datos/Ticket/ClienteId")->item(0)->nodeValue;
            $equipo = $xpath->query("//Datos/Ticket/Equipo")->item(0)->nodeValue;
            $descripcion = $xpath->query("//Datos/Ticket/Descripcion")->item(0)->nodeValue;
            $fecha = $xpath->query("//Datos/Ticket/FechaIngreso")->item(0)->nodeValue;

            echo "🛠 Operación solicitada: $operacion\n";
            echo "📦 Equipo recibido vía XPath: $equipo\n";

            // Aquí instanciaríamos la entidad, guardaríamos en BD, etc...

            // Generamos respuesta de Éxito en XML
            $respuestaXML = '<?xml version="1.0" encoding="UTF-8"?><Mensaje><Tipo>Response</Tipo><Estado>Exito</Estado><Datos><Respuesta>Ticket de ' . $equipo . ' registrado correctamente vía XML.</Respuesta><IdGenerado>99</IdGenerado></Datos></Mensaje>';
            
            socket_write($client, $respuestaXML, strlen($respuestaXML));
        }

        socket_close($client);
    }
}
socket_close($socket);
?>
>>>>>>> 9a10ca8 (CODIGO UPDATE)
