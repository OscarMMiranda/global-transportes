<?php
// ======================================================
//  archivo: marcar_actual.php
//  RESPONSABILIDAD: marcar una versión como actual
//  COMPATIBLE: PHP 5.6 + IIS
// ======================================================

require_once __DIR__ . '/../../../config/conexion.php';

$vehiculo_id = isset($_POST['vehiculo_id']) ? intval($_POST['vehiculo_id']) : 0;
$tipo_documento_id = isset($_POST['tipo_documento_id']) ? intval($_POST['tipo_documento_id']) : 0;
$documento_id = isset($_POST['documento_id']) ? intval($_POST['documento_id']) : 0;

if ($vehiculo_id <= 0 || $tipo_documento_id <= 0 || $documento_id <= 0) {
    echo json_encode(array('ok' => false, 'mensaje' => 'Parámetros incompletos'));
    exit;
}

// 1. Poner todas las versiones en 0
$sql1 = "
    UPDATE documentos_vehiculos
    SET is_current = 0
    WHERE vehiculo_id = $vehiculo_id
      AND tipo_documento_id = $tipo_documento_id
";

$conexion->query($sql1);

// 2. Marcar la versión seleccionada como actual
$sql2 = "
    UPDATE documentos_vehiculos
    SET is_current = 1
    WHERE id = $documento_id
    LIMIT 1
";

$ok = $conexion->query($sql2);

echo json_encode(array(
    'ok' => $ok ? true : false,
    'mensaje' => $ok ? 'Versión marcada como actual' : 'Error al actualizar'
));
