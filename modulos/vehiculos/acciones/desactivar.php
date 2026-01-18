<?php
// archivo: /modulos/vehiculos/acciones/desactivar.php

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// MISMA RUTA QUE ver.php (4 niveles arriba)
require_once __DIR__ . '/../../../includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';
require_once INCLUDES_PATH . '/funciones.php';

$conn = getConnection();
if (!$conn) {
    echo json_encode(array("ok" => false, "msg" => "Error de conexión"));
    exit;
}

// ID del vehículo (compatible con PHP 5.6)
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    echo json_encode(array("ok" => false, "msg" => "ID inválido"));
    exit;
}

// Usuario que desactiva (compatible con PHP 5.6)
$usuario_id = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

// SQL
$sql = "
UPDATE vehiculos 
SET activo = 0,
    fecha_borrado = NOW(),
    borrado_por = ?
WHERE id = ?
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
        "msg" => "Error al desactivar",
        "error_sql" => $stmt->error
    ));
    exit;
}

echo json_encode(array(
    "ok" => true,
    "msg" => "Vehículo desactivado correctamente"
));