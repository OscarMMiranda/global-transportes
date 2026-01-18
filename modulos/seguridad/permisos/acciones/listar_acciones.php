<?php
// archivo: /modulos/seguridad/permisos/acciones/listar_acciones.php

header('Content-Type: application/json');

// Cargar configuración global
require_once __DIR__ . '/../../../../includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar permiso correcto del módulo de permisos
requirePermiso("permisos", "ver");

$conn = getConnection();

if (!$conn) {
    echo json_encode([
        "ok"   => false,
        "msg"  => "Error de conexión a la base de datos.",
        "data" => []
    ]);
    exit;
}

$sql = "SELECT id, nombre FROM acciones ORDER BY nombre ASC";
$result = $conn->query($sql);

$acciones = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $acciones[] = [
            "id"     => (int)$row["id"],
            "nombre" => $row["nombre"]
        ];
    }
}

echo json_encode([
    "ok"   => true,
    "msg"  => "Acciones cargadas correctamente.",
    "data" => $acciones
]);
exit;