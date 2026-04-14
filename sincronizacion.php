<?php
// Archivo: sincronizacion.php
require_once __DIR__ . '/app/Services/ServiceConnector.php';
require_once __DIR__ . '/app/Core/conexion.php';

echo "<h2>Guía 7 - Prueba de Estrés y Sincronización Web Service</h2>";

// Instanciamos el servicio y ejecutamos
$servicio = new ServiceConnector();
$resultado = $servicio->sincronizarClientesExternos();

// Mostrar mensaje de éxito/error
if ($resultado['status'] == 'success') {
    echo "<p style='color:green; font-weight:bold;'>" . $resultado['message'] . "</p>";
} else {
    echo "<p style='color:red; font-weight:bold;'>" . $resultado['message'] . "</p>";
}

// Consultar la base de datos para verificar que entraron los datos
$db = Conexion::getInstancia()->getConexion();
$stmt = $db->query("SELECT * FROM cliente");
$listaClientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h3>Listado de Clientes en MySQL (Incluyendo los importados de la API):</h3>";
echo "<pre>";
print_r($listaClientes);
echo "</pre>";
?>