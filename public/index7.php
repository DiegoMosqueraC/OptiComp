<?php

echo "<h1>Proyecto Iniciado</h1>";

// Conexión a la base de datos
include 'app/Data/conexion.php';

echo "<hr>";


$json_externo = '{
    "servicio": "Auth_Global",
    "status": "Conectado",
    "latencia": "20ms"
}';


$datos_api = json_decode($json_externo);

// Validación 
if ($datos_api !== null) {
    echo "<h2>Estado de Comunicación Externa</h2>";
    echo "Servicio: " . $datos_api->servicio . "<br>";
    echo "Estado: " . $datos_api->status . "<br>";
    echo "Latencia: " . $datos_api->latencia . "<br>";
    echo "Respuesta: Comunicación establecida correctamente.";
} else {
    echo "Error al procesar los datos externos.";
}

?>
