<?php
// archivo: /modulos/vehiculos/acciones/restaurar.php

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Sesión
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

// Usuario que restaura
$usuario_id = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

// SQL
$sql = "
UPDATE vehiculos 
SET activo = 1,
    fecha_borrado = NULL,
    borrado_por = NULL
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

$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    echo json_encode(array(
        "ok" => false,
        "msg" => "Error al restaurar",
        "error_sql" => $stmt->error
    ));
    exit;
}

echo json_encode(array(
    "ok" => true,
    "msg" => "Vehículo restaurado correctamente"
));