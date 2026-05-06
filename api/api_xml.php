<?php

header("Content-Type: text/xml");

// Leer XML recibido
$xml_string = file_get_contents("php://input");

if (!$xml_string) {
    echo "<response><status>ERROR</status><mensaje>No se recibió XML</mensaje></response>";
    exit;
}

// Validar XML
libxml_use_internal_errors(true);
$xml = simplexml_load_string($xml_string);

if ($xml === false) {
    echo "<response><status>ERROR</status><mensaje>XML inválido</mensaje></response>";
    exit;
}

// Obtener operación
$operacion = (string)$xml->operacion;

// Conexión a base de datos (ajústala si es necesario)
$conn = new mysqli("localhost", "root", "", "opticomp");

if ($conn->connect_error) {
    echo "<response><status>ERROR</status><mensaje>Error conexión DB</mensaje></response>";
    exit;
}

// Lógica de operaciones
switch ($operacion) {

    case "registrarProducto":

        $nombre = (string)$xml->datos->nombre;
        $precio = (float)$xml->datos->precio;

        $sql = "INSERT INTO productos (nombre, precio) VALUES ('$nombre', '$precio')";

        if ($conn->query($sql)) {
            echo "<response><status>OK</status><mensaje>Producto registrado</mensaje></response>";
        } else {
            echo "<response><status>ERROR</status><mensaje>Error al guardar</mensaje></response>";
        }

        break;

    case "consultarProductos":

        $result = $conn->query("SELECT nombre, precio FROM productos");

        echo "<response><status>OK</status><productos>";

        while ($row = $result->fetch_assoc()) {
            echo "<producto>";
            echo "<nombre>{$row['nombre']}</nombre>";
            echo "<precio>{$row['precio']}</precio>";
            echo "</producto>";
        }

        echo "</productos></response>";

        break;

    default:
        echo "<response><status>ERROR</status><mensaje>Operación inválida</mensaje></response>";
}

$conn->close();