<?php
// cancelar.php — Anula una asignación activa


ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once __DIR__ . '/../modelo.php';
require_once __DIR__ . '/../funciones.php';

header('Content-Type: application/json');
validarSesionAdmin();

$conn = getConnection();

// Validar ID recibido
$asignacionId = isset($_POST['asignacion_id']) ? intval($_POST['asignacion_id']) : 0;
if ($asignacionId <= 0) {
    echo json_encode(array(
        'success' => false,
        'message' => 'ID de asignación inválido.'
    ));
    exit;
}

// Auditoría
$usuarioId = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 0;
$ipOrigen  = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';

// Verificar existencia y estado
$asignacion = getAsignacionPorId($conn, $asignacionId);
if (!$asignacion) {
    echo json_encode(array(
        'success' => false,
        'message' => 'Asignación no encontrada.'
    ));
    exit;
}

$estadoActivo = getEstadoId($conn, 'activo');
$estadoCancelado = getEstadoId($conn, 'anulado');

if ($asignacion['estado_id'] != $estadoActivo) {
    echo json_encode(array(
        'success' => false,
        'message' => 'Solo se pueden cancelar asignaciones activas.'
    ));
    exit;
}

// Cancelar asignación
$sql = "
    UPDATE asignaciones_conductor
       SET estado_id        = ?,
           fecha_borrado    = NOW(),
           borrado_por      = ?,
           ip_origen        = ?,
           updated_at       = NOW()
     WHERE id = ?
";
$stmt = $conn->prepare($sql);
if (! $stmt) {
    echo json_encode(array(
        'success' => false,
        'message' => 'Error en prepare: ' . $conn->error
    ));
    exit;
}
$stmt->bind_param('iisi', $estadoCancelado, $usuarioId, $ipOrigen, $asignacionId);
if (! $stmt->execute()) {
    echo json_encode(array(
        'success' => false,
        'message' => 'Error al cancelar: ' . $stmt->error
    ));
    exit;
}
$stmt->close();

// Registrar trazabilidad
$sql2 = "
    INSERT INTO asignaciones_historial
        (asignacion_id, usuario_id, accion, fecha)
    VALUES (?, ?, 'Anulado', NOW())
";
$stmt2 = $conn->prepare($sql2);
if ($stmt2) {
    $stmt2->bind_param('ii', $asignacionId, $usuarioId);
    $stmt2->execute();
    $stmt2->close();
}

// Respuesta JSON
echo json_encode(array(
    'success' => true,
    'message' => 'Asignación cancelada correctamente.'
));
exit;