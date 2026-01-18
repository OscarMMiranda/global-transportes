<?php
// archivo: /modulos/seguridad/permisos/acciones/guardar_permisos.php

header('Content-Type: application/json');

require_once __DIR__ . '/../../../../includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// El permiso correcto es del módulo "permisos"
requirePermiso("permisos", "editar");

if (!isset($_POST['rol_id']) || !isset($_POST['modulo_id'])) {
    echo json_encode(["ok" => false, "msg" => "Parámetros incompletos."]);
    exit;
}

$rol_id    = (int)$_POST['rol_id'];
$modulo_id = (int)$_POST['modulo_id'];
$acciones  = isset($_POST['acciones']) ? $_POST['acciones'] : [];

$conn = getConnection();

// 1. Eliminar permisos actuales
$sqlDel = "DELETE FROM permisos_roles WHERE rol_id = ? AND modulo_id = ?";
$stmtDel = $conn->prepare($sqlDel);
$stmtDel->bind_param("ii", $rol_id, $modulo_id);
$stmtDel->execute();

// 2. Insertar nuevos permisos
$sqlIns = "INSERT INTO permisos_roles (rol_id, modulo_id, accion_id) VALUES (?, ?, ?)";
$stmtIns = $conn->prepare($sqlIns);

foreach ($acciones as $accion_id) {
    $accion_id = (int)$accion_id;
    $stmtIns->bind_param("iii", $rol_id, $modulo_id, $accion_id);
    $stmtIns->execute();
}

echo json_encode([
    "ok"  => true,
    "msg" => "Permisos actualizados correctamente."
]);
exit;