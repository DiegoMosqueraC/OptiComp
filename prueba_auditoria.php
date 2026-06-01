<?php
require_once __DIR__ . '/app/Core/Logger.php';

echo "Ejecutando pruebas de trazabilidad y auditoría...\n\n";

// Acción 1: Simular un login fallido
Logger::log('SEGURIDAD', 'Intento de inicio de sesión fallido. Usuario: admin@fesc.edu.co. IP: 192.168.1.45');
echo "✔ Evento de seguridad registrado.\n";

// Acción 2: Simular la inserción de un registro
Logger::log('OPERACION', 'Se insertó exitosamente un nuevo Ticket (ID: 105) en la base de datos.');
echo "✔ Evento de operación registrado.\n";

// Acción 3: Simular un error de base de datos o consulta de dato
Logger::log('ERROR_SISTEMA', 'Excepción PDO capturada: Connection refused. La base de datos no responde.');
echo "✔ Evento de error registrado.\n";

echo "\nPruebas finalizadas. Por favor, revisa el archivo creado en /logs/audit.log\n";
?>