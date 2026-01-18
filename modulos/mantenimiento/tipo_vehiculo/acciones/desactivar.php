<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/acciones/desactivar.php
header('Content-Type: application/json');

// Cargar configuración global (rutas, conexión, funciones, etc.)
require_once __DIR__ . '/../../../../includes/config.php';
// Cargar sistema de permisos usando la ruta ya definida
require_once INCLUDES_PATH . '/permisos.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

requirePermiso("tipo_vehiculo", "desactivar");

// Validar ID
if (!isset($_POST['id']) || (int)$_POST['id'] <= 0) {
    echo json_encode(array("ok" => false, "msg" => "ID inválido."));
    exit;
}

$id   = (int)$_POST['id'];
$conn = getConnection();

if (!$conn) {
    echo json_encode(array("ok" => false, "msg" => "Error de conexión a la base de datos."));
    exit;
}

// Verificar existencia y estado actual
$sql  = "SELECT eliminado FROM tipo_vehiculo WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(array("ok" => false, "msg" => "Error al preparar la consulta."));
    exit;
}

$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($eliminado);

if (!$stmt->fetch()) {
    $stmt->close();
    echo json_encode(array("ok" => false, "msg" => "El registro no existe."));
    exit;
}
$stmt->close();

// Ya está desactivado
if ((int)$eliminado === 1) {
    echo json_encode(array("ok" => false, "msg" => "El registro ya está desactivado."));
    exit;
}

// Desactivar
$sql = "
    UPDATE tipo_vehiculo 
    SET eliminado = 1, fecha_borrado = CURRENT_TIMESTAMP() 
    WHERE id = ?
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(array("ok" => false, "msg" => "Error al preparar la actualización."));
    exit;
}

$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    $error = $stmt->error;
    $stmt->close();
    echo json_encode(array(
        "ok"   => false,
        "msg"  => "Error al desactivar el registro.",
        "error"=> $error
    ));
    exit;
}

$stmt->close();

// Registrar historial (si hay usuario en sesión)
$usuario_id = isset($_SESSION["usuario_id"]) ? (int)$_SESSION["usuario_id"] : 0;

if ($usuario_id > 0) {
    $sqlHist = "
        INSERT INTO tipo_vehiculo_historial (tipo_id, usuario_id, cambio, fecha)
        VALUES (?, ?, ?, CURRENT_TIMESTAMP())
    ";
    $stmtHist = $conn->prepare($sqlHist);

    if ($stmtHist) {
        $cambio = "Desactivación del tipo de vehículo";
        $stmtHist->bind_param("iis", $id, $usuario_id, $cambio);
        $stmtHist->execute();
        $stmtHist->close();
    }
}

echo json_encode(array("ok" => true, "msg" => "Registro desactivado correctamente."));
exit;