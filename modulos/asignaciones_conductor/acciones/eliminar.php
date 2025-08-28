<?php
// eliminar.php — Finalización lógica de una asignación

session_start();

// 1. Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors',     1);
ini_set('error_log',      __DIR__ . '/error_log.txt');

// 2. Cargar configuración y conexión
require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

// 3. Validar sesión
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado."]);
    exit();
}
$usuario_id = intval($_SESSION['usuario_id']);

// 4. Validar solicitud AJAX
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// 5. Obtener ID de asignación
$asignacion_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($asignacion_id <= 0) {
    echo json_encode(["success" => false, "message" => "ID de asignación inválido."]);
    exit();
}

// 6. Obtener ID del estado "finalizado"
$sql_estado = "SELECT id FROM estado_asignacion WHERE nombre = 'finalizado'";
$result_estado = $conn->query($sql_estado);
if (!$result_estado || $result_estado->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "No se encontró el estado 'finalizado'."]);
    exit();
}
$estado_finalizado_id = intval($result_estado->fetch_assoc()['id']);

// 7. Finalizar asignación (actualización lógica)
$sql_finalizar = "
    UPDATE asignaciones_conductor
    SET estado_id = ?, 
        fecha_fin = NOW(),
        fecha_borrado = NOW(),
        borrado_por = ?,
        updated_at = NOW()
    WHERE id = ?
";

$stmt = $conn->prepare($sql_finalizar);
if ($stmt) {
    $stmt->bind_param("iii", $estado_finalizado_id, $usuario_id, $asignacion_id);
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Asignación finalizada correctamente."]);
    } else {
        error_log("❌ Error al finalizar asignación: " . $stmt->error);
        echo json_encode(["success" => false, "message" => "Error al finalizar: " . $stmt->error]);
    }
    $stmt->close();
} else {
    error_log("❌ Error en prepare: " . $conn->error);
    echo json_encode(["success" => false, "message" => "Error en la preparación de la consulta."]);
}
?>
