<?php
session_start();

// 1) Modo depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/funciones.php';

$conn = getConnection();

// 2) Validar sesión
if (!isset($_SESSION['usuario'])) {
    die("❌ Acceso no autorizado.");
}
$usuario     = $_SESSION['usuario'];
$usuario_id  = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 0;
$ip          = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
$userAgent   = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'desconocido';
$fechaActual = date('Y-m-d H:i:s');

// 3) Validar método y parámetro
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id']) || !is_numeric($_POST['id'])) {
    die("❌ Solicitud inválida.");
}
$vehiculo_id = (int) $_POST['id'];

// 4) Verificar existencia y estado
$stmt = $conn->prepare("SELECT placa, estado_id FROM vehiculos WHERE id = ? AND activo = 1");
$stmt->bind_param("i", $vehiculo_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("❌ Vehículo no encontrado o ya eliminado.");
}
$registro = $result->fetch_assoc();
$placa    = $registro['placa'];
$estado_anterior_id = (int) $registro['estado_id'];
$estado_txt_anterior = obtenerNombreEstado($conn, $estado_anterior_id);

// 5) Actualizar vehículo (baja lógica + trazabilidad nativa)
$stmt = $conn->prepare("
    UPDATE vehiculos 
    SET activo = 0, fecha_borrado = ?, borrado_por = ? 
    WHERE id = ?
");
if (!$stmt) {
    die("❌ Error al preparar actualización: " . $conn->error);
}
$stmt->bind_param("sii", $fechaActual, $usuario_id, $vehiculo_id);
if (!$stmt->execute()) {
    die("❌ Error al ejecutar baja lógica: " . $stmt->error);
}

// 6) Insertar en vehiculo_historial
$estado_txt_nuevo = 'inactivo';
$motivo = "Eliminación lógica desde panel";

$stmtHist = $conn->prepare("
    INSERT INTO vehiculo_historial 
    (vehiculo_id, estado_anterior, estado_nuevo, motivo, usuario_id, ip_origen, user_agent, fecha)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");
if ($stmtHist) {
    $stmtHist->bind_param(
        "isssisss",
        $vehiculo_id,
        $estado_txt_anterior,
        $estado_txt_nuevo,
        $motivo,
        $usuario_id,
        $ip,
        $userAgent,
        $fechaActual
    );
    $stmtHist->execute();
}

// 7) Registrar en historial_bd
registrarEnHistorial($conn, $usuario, "Desactivó vehículo ID $vehiculo_id", "vehiculos", $ip);

// 8) Redirección
$_SESSION['msg'] = "Vehículo “{$placa}” eliminado lógicamente.";
header("Location: vehiculos.php");
exit;

// Función auxiliar compatible con PHP 5.6
function obtenerNombreEstado($conn, $estado_id) {
    $sql = "SELECT nombre FROM estado_vehiculo WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return 'desconocido';
    $stmt->bind_param("i", $estado_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return ($result->num_rows > 0) ? $result->fetch_assoc()['nombre'] : 'desconocido';
}
?>