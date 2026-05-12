<?php
// Clase que contiene las operaciones definidas en el WSDL
class TicketServiceSOAP {
    public function crearTicket($request) {
        // Extraemos los datos que nos envía el cliente
        $equipo = $request->Equipo;
        
        // Aquí iría la lógica de guardar en base de datos usando TicketDAO...

        // Retornamos un arreglo que coincida exactamente con <TicketResponse> del WSDL
        return [
            "Estado" => "Exito",
            "IdGenerado" => rand(100, 999), // Simulamos un ID generado
            "Mensaje" => "El ticket para el equipo '$equipo' fue creado exitosamente vía SOAP."
        ];
    }
}

// Desactivamos la caché del WSDL para que no nos de problemas mientras programamos
ini_set("soap.wsdl_cache_enabled", "0");

// Instanciamos el servidor nativo de PHP pasándole nuestro contrato
$server = new SoapServer("TicketService.wsdl");

// Le decimos qué clase tiene la lógica de las operaciones
$server->setClass("TicketServiceSOAP");

// Ponemos a escuchar al servidor
$server->handle();
?>