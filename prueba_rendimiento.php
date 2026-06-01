<?php
// Script de prueba para Actividad 3: Optimización del Paso de Parámetros
require_once __DIR__ . '/app/Core/Ticket.php';

// Creamos un ticket simulado con datos reales
$ticket = new Ticket(102, "Laptop Gamer ASUS ROG", "La tarjeta de video presenta artefactos en pantalla al jugar, sobrecalentamiento excesivo", "Abierto", "2026-02-15");

// ESTRATEGIA 1: Paso por Valor (Serialización completa de los atributos)
$payloadPorValor = json_encode([
    "accion" => "actualizar",
    "ticket" => [
        "id" => 55,
        "equipo" => $ticket->getEquipo(),
        "descripcion" => $ticket->getDescripcion(),
        "estado" => $ticket->getEstado()
    ]
]);

// ESTRATEGIA 2: Paso por Referencia Lógica
// Solo enviamos el ID, obligando al servidor a buscar los datos en su Base de Datos
$payloadPorReferencia = json_encode([
    "accion" => "actualizar",
    "ticket_ref_id" => 55
]);

echo "=== PRUEBA DE RENDIMIENTO: TAMAÑO DE PAYLOAD ===\n\n";
echo "1. Paso por Valor (Estado Completo):\n";
echo "Payload: " . $payloadPorValor . "\n";
echo "Peso en red: " . strlen($payloadPorValor) . " bytes\n\n";

echo "2. Paso por Referencia Lógica (Solo Puntero/ID):\n";
echo "Payload: " . $payloadPorReferencia . "\n";
echo "Peso en red: " . strlen($payloadPorReferencia) . " bytes\n\n";

$ahorro = strlen($payloadPorValor) - strlen($payloadPorReferencia);
echo "Ahorro de ancho de banda: " . $ahorro . " bytes por petición.\n";
?>