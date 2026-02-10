<?php
// archivo: /modulos/asistencias/acciones/listar_asistencias.php

require __DIR__ . '/../../../includes/config.php';
require __DIR__ . '/../core/asistencia.func.php';

header('Content-Type: application/json; charset=utf-8');

// conexión real del sistema
$conn = $GLOBALS['db'];

if (!$conn instanceof mysqli) {
    echo json_encode([
        "data" => [],
        "error" => "Sin conexión a la base de datos"
    ]);
    exit;
}

// obtener asistencias desde tu función centralizada
$asistencias = obtener_asistencias($conn);

// DataTables requiere un array con clave "data"
echo json_encode([
    "data" => $asistencias
]);
