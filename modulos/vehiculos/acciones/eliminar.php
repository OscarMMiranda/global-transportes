<?php
// archivo: /modulos/vehiculos/acciones/eliminar.php

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../includes/config.php';
require_once INCLUDES_PATH . '/funciones.php';

$conn = getConnection();
if (!$conn) {
    echo json_encode(array("ok" => false, "msg" => "Error de conexión"));
    exit;
}

// ID del vehículo
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    echo json_encode(array("ok" => false, "msg" => "ID inválido"));
    exit;
}

// Usuario que elimina
$usuario_id = isset($_SESSION['usuario']) ? intval($_SESSION['usuario']) : 0;

// SQL — Soft delete REAL según tu tabla
$sql = "
UPDATE vehiculos 
SET 
    activo = 0,
    fecha_borrado = NOW(),
    borrado_por = ?
WHERE id = ?
LIMIT 1
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(array(
        "ok" => false,
        "msg" => "Error interno",
        "error_sql" => $conn->error
    ));
    exit;
}

$stmt->bind_param("ii", $usuario_id, $id);

if (!$stmt->execute()) {
    echo json_encode(array(
        "ok" => false,
        "msg" => "Error al eliminar",
        "error_sql" => $stmt->error
    ));
    exit;
}

echo json_encode(array(
    "ok" => true,
    "msg" => "Vehículo eliminado correctamente"
));
