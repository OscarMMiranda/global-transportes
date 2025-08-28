<?php
// test_finalizar.php

// 1) Carga configuración y el modelo
require_once __DIR__ . '/includes/config.php';            // ajusta la ruta si es otra
require_once __DIR__ . '/modulos/asignaciones_conductor/modelo.php';

// 2) Conecta a la DB
$conn = getConnection();
if (! $conn) {
    die("No pude conectar a la base de datos.");
}

// 3) Parámetros de prueba
$idPrueba   = 15;  // pon aquí un ID válido de asignación activa
$userPrueba = 1;   // tu usuario de pruebas

// 4) Instrumenta trazas directas en el modelo
error_log("🧪 [TEST] Invocando finalizarAsignacion con ID=$idPrueba, Usuario=$userPrueba");

// 5) Llama a la función
$result = finalizarAsignacion($conn, $idPrueba, $userPrueba);

// 6) Muestra el resultado y el estado real en BD
echo "<pre>";
var_dump($result);

$row = $conn->query("
    SELECT id, estado_id, fecha_fin, borrado_por 
    FROM asignaciones_conductor 
    WHERE id = $idPrueba
")->fetch_assoc();
echo "\nEstado en BD:\n";
var_export($row);
echo "</pre>";