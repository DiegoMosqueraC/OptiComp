<?php
// Actividad arquitectura diseño
require_once "app/Core/Ticket.php";
require_once "app/Data/TicketDAO.php";

$ticket = new Ticket(
    1,
    "Laptop ASUS",
    "No enciende",
    "Pendiente",
    date("Y-m-d")
);

$dao = new TicketDAO();

$resultado = $dao->crear($ticket);

echo "<h2>Resultado:</h2>";
echo $resultado;
