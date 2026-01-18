<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/acciones/restaurar.php
header('Content-Type: application/json');

require_once __DIR__ . '/../../../../includes/conexion.php';
require_once __DIR__ . '/../../../../includes/permisos.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

requirePermiso("tipo_vehiculo", "activar");

if (!isset($_POST['id']) || (int)$_POST['id'] <= 0) {
    echo json_encode(["ok" => false, "msg" => "ID inválido."]);
    exit;
}

$id = (int)$_POST['id'];
$conn = getConnection();

// Verificar existencia y estado actual
$sql = "SELECT eliminado FROM tipo_vehiculo WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($eliminado);
if (!$stmt->fetch()) {
    echo json_encode(["ok" => false, "msg" => "El registro no existe."]);
    exit;
}
$stmt->close();

// Si ya está activo
if ($eliminado == 0) {
    echo json_encode(["ok" => false, "msg" => "El registro ya está activo."]);
    exit;
}

// Activar
$sql = "UPDATE tipo_vehiculo SET eliminado = 0, fecha_borrado = NULL WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    // Registrar historial
    $usuario_id = isset($_SESSION["usuario_id"]) ? (int)$_SESSION["usuario_id"] : 0;

    $sqlHist = "
        INSERT INTO tipo_vehiculo_historial (tipo_id, usuario_id, cambio, fecha)
        VALUES (?, ?, ?, NOW())
    ";
    $stmtHist = $conn->prepare($sqlHist);
    $cambio = "Restauración (activación) del tipo de vehículo";
    $stmtHist->bind_param("iis", $id, $usuario_id, $cambio);
    $stmtHist->execute();
    $stmtHist->close();

    echo json_encode(["ok" => true, "msg" => "Registro activado correctamente."]);
} else {
    echo json_encode(["ok" => false, "msg" => "No se pudo activar el registro."]);
}

$stmt->close();
exit;