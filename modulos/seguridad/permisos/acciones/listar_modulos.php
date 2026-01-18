<?php
// archivo: /modulos/seguridad/permisos/acciones/listar_modulos.php

header('Content-Type: application/json');

// Cargar configuración global
require_once __DIR__ . '/../../../../includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';

// Validar permiso (ajusta si tu módulo de seguridad usa otro permiso)
requirePermiso("permisos", "ver");

$conn = getConnection();

if (!$conn) {
    echo json_encode(array(
        "ok"   => false,
        "msg"  => "Error de conexión a la base de datos.",
        "data" => array()
    ));
    exit;
}

$sql = "SELECT id, nombre FROM modulos ORDER BY nombre ASC";
$result = $conn->query($sql);

$modulos = array();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $modulos[] = array(
            "id"     => (int)$row["id"],
            "nombre" => $row["nombre"]
        );
    }
}

echo json_encode(array(
    "ok"   => true,
    "msg"  => "Módulos cargados correctamente.",
    "data" => $modulos
));
exit;