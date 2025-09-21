<?php
session_start();

// 1) Cargar configuración y funciones
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/funciones.php';

$conn = getConnection();

// 2) Validar sesión
if (!isset($_SESSION['usuario'])) {
    $_SESSION['error'] = "Acceso no autorizado.";
    header("Location: vehiculos.php");
    exit;
}
$usuario     = $_SESSION['usuario'];
$usuario_id  = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 0;
$ip          = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
$userAgent   = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'desconocido';
$fechaActual = date('Y-m-d H:i:s');

// 3) Validar método y parámetro
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id']) || !is_numeric($_POST['id'])) {
    $_SESSION['error'] = "Solicitud inválida.";
    header("Location: vehiculos.php");
    exit;
}
$vehiculo_id = (int) $_POST['id'];

// 4) Verificar existencia y estado actual
$stmt = $conn->prepare("SELECT placa, estado_id FROM vehiculos WHERE id = ? AND activo = 0");
$stmt->bind_param("i", $vehiculo_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    $_SESSION['error'] = "Vehículo no encontrado o ya activo.";
    header("Location: vehiculos.php");
    exit;
}
$registro = $result->fetch_assoc();
$placa    = $registro['placa'];
$estado_anterior_id = (int) $registro['estado_id'];
$estado_txt_anterior = obtenerNombreEstado($conn, $estado_anterior_id);

// 5) Restaurar vehículo con trazabilidad nativa
$stmt = $conn->prepare("
    UPDATE vehiculos 
    SET activo = 1, fecha_modificacion = ?, modificado_por = ? 
    WHERE id = ?
");
if (!$stmt) {
    $_SESSION['error'] = "Error al preparar restauración: " . $conn->error;
    header("Location: vehiculos.php");
    exit;
}
$stmt->bind_param("sii", $fechaActual, $usuario_id, $vehiculo_id);
if (!$stmt->execute()) {
    $_SESSION['error'] = "Error al ejecutar restauración: " . $stmt->error;
    header("Location: vehiculos.php");
    exit;
}

// 6) Insertar en vehiculo_historial
$estado_txt_nuevo = 'activo';
$motivo = "Restauración manual desde panel";

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
registrarEnHistorial(
    $conn,
    isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'visitante',
    "Consultó vehículo inactivo ID $id",
    "vehiculos",
    obtenerIP()
);
// 8) Redirección
$_SESSION['msg'] = "Vehículo “{$placa}” reactivado correctamente.";
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