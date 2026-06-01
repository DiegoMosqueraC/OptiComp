<?php

namespace App\Controllers;

use App\Services\ApiXmlService;

class ApiController
{
    public function xml(): void
    {
        header('Content-Type: text/xml; charset=UTF-8');
        $xmlInput = file_get_contents('php://input');
        if (empty($xmlInput)) {
            echo '<response><status>ERROR</status><mensaje>No se recibio XML</mensaje></response>';
            return;
        }
        $service = new ApiXmlService();
        echo $service->procesarRequest($xmlInput);
    }
}
