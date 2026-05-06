<?php

$xml = '
<request>
    <operacion>registrarProducto</operacion>
 
';

$options = [
    "http" => [
        "header" => "Content-Type: text/xml",
        "method" => "POST",
        "content" => $xml
    ]
];

$context = stream_context_create($options);

$response = file_get_contents("http://localhost/OptiComp/api/api_xml.php", false, $context);

echo "<pre>";
echo htmlspecialchars($response);
echo "</pre>";