<?php
// Actividad arquitectura diseño
require_once __DIR__ . '/app/Core/TicketDAO.php';
require_once __DIR__ . '/app/Core/Ticket.php';

echo "<h2>Simulación Caso Testigo - Guía 6</h2>";

// 1. Instanciar un objeto de negocio con datos de prueba
// Nota: Usamos cliente_id = 1, el cual creamos en los Seeders SQL
$nuevoTicket = new Ticket(
    1, 
    "Portátil Lenovo ThinkPad", 
    "Pantalla no enciende tras actualización", 
    "Abierto", 
    date("Y-m-d")
);

echo "<h3>1. Objeto a persistir:</h3>";
echo "<pre>";
var_dump($nuevoTicket);
echo "</pre>";

// 2. Llamar a la función de persistencia (DAO)
$dao = new TicketDAO();
$resultado = $dao->crear($nuevoTicket);

// 3. Capturar y mostrar mensaje de confirmación
echo "<h3>2. Resultado de la Base de Datos:</h3>";
echo "<p style='color:green; font-weight:bold;'>" . $resultado . "</p>";

// 4. Mostrar que efectivamente se guardó (Validación adicional)
echo "<h3>3. Listado actual de Tickets en la BD:</h3>";
$listaTickets = $dao->obtenerTodos();
echo "<pre>";
print_r($listaTickets);
echo "</pre>";
?>
