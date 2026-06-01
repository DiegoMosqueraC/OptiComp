<?php
// 1. Simulamos el XML que envía el cliente
$xml_string = '<?xml version="1.0" encoding="UTF-8"?>
<Mensaje>
    <Datos>
        <Ticket>
            <ClienteId>102</ClienteId>
            <Equipo>Laptop HP Pavilion</Equipo>
            <Descripcion>No enciende la pantalla</Descripcion>
            <FechaIngreso>2026-02-15</FechaIngreso>
        </Ticket>
    </Datos>
</Mensaje>';

$xml = new DOMDocument;
$xml->loadXML($xml_string);

// 2. Cargamos el archivo de transformación XSLT
$xsl = new DOMDocument;
$xsl->load('transformacion.xsl');

// 3. Aplicamos la transformación
$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl);

echo "A continuación se muestra el HTML generado a partir del XML:\n\n";
echo $proc->transformToXML($xml);
?>