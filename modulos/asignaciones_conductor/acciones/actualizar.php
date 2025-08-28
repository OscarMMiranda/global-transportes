<?php
// actualizar.php — Procesa edición de asignación activa

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once __DIR__ . '/../modelo.php';
require_once __DIR__ . '/../funciones.php';

validarSesionAdmin();

$conn = getConnection();

// Validar ID desde GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("ID de asignación inválido.");
}

// Validar campos desde POST
$tracto_id    = isset($_POST['vehiculo_tracto_id']) ? intval($_POST['vehiculo_tracto_id']) : 0;
$remolque_id  = isset($_POST['vehiculo_remolque_id']) ? intval($_POST['vehiculo_remolque_id']) : 0;
$conductor_id = isset($_POST['conductor_id']) ? intval($_POST['conductor_id']) : 0;

if ($tracto_id <= 0 || $remolque_id <= 0 || $conductor_id <= 0) {
    die("Todos los campos son obligatorios.");
}

// Auditoría
$usuario_id = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 0;
$ip_origen  = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';

// Verificar existencia y estado
$asignacion = getAsignacionPorId($conn, $id);
if (!$asignacion) {
    die("Asignación no encontrada.");
}

$estadoActivo = getEstadoId($conn, 'activo');
if ($asignacion['estado_id'] != $estadoActivo) {
    die("Solo se pueden editar asignaciones activas.");
}

// Actualizar asignación
$sql = "
    UPDATE asignaciones_conductor
       SET conductor_id         = ?,
           vehiculo_tracto_id   = ?,
           vehiculo_remolque_id = ?,
           updated_at           = NOW(),
           modificado_por       = ?,
           ip_origen            = ?
     WHERE id = ?
";
$stmt = $conn->prepare($sql);
if (! $stmt) {
    die("Error en prepare: " . $conn->error);
}
$stmt->bind_param('iiiisi', $conductor_id, $tracto_id, $remolque_id, $usuario_id, $ip_origen, $id);
if (! $stmt->execute()) {
    die("Error al actualizar: " . $stmt->error);
}
$stmt->close();

// Registrar trazabilidad
$sql2 = "
    INSERT INTO asignaciones_historial
        (asignacion_id, usuario_id, accion, fecha)
    VALUES (?, ?, 'Editado', NOW())
";
$stmt2 = $conn->prepare($sql2);
if ($stmt2) {
    $stmt2->bind_param('ii', $id, $usuario_id);
    $stmt2->execute();
    $stmt2->close();
}

// Redirigir al listado
header("Location: index.php?action=list");
exit;