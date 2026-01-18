<?php
// archivo: /modulos/seguridad/permisos/acciones/listar_permisos.php

header('Content-Type: application/json');

require_once __DIR__ . '/../../../../includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// El permiso correcto es del módulo "permisos"
requirePermiso("permisos", "ver");

if (!isset($_GET['rol_id']) || !isset($_GET['modulo_id'])) {
    echo json_encode([
        "ok"   => false,
        "msg"  => "Parámetros incompletos.",
        "data" => []
    ]);
    exit;
}

$rol_id    = (int)$_GET['rol_id'];
$modulo_id = (int)$_GET['modulo_id'];

$conn = getConnection();

$sql = "
    SELECT accion_id 
    FROM permisos_roles 
    WHERE rol_id = ? AND modulo_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $rol_id, $modulo_id);
$stmt->execute();
$result = $stmt->get_result();

$acciones = [];
while ($row = $result->fetch_assoc()) {
    $acciones[] = (int)$row["accion_id"];
}

echo json_encode([
    "ok"   => true,
    "msg"  => "Permisos cargados.",
    "data" => $acciones
]);
exit;