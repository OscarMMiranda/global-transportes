<?php

// archivo: /modulos/documentos_vehiculos/acciones/listar_historial_vehiculo.php

error_reporting(E_ALL);
ini_set('display_errors', 0); // JSON limpio
ini_set('log_errors', 1);

header('Content-Type: application/json; charset=utf-8');

// Limpia cualquier salida previa
if (function_exists('ob_clean')) { ob_clean(); }

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

$vehiculo_id      = isset($_POST['vehiculo_id']) ? intval($_POST['vehiculo_id']) : 0;
$tipo_documento_id = isset($_POST['tipo_documento_id']) ? intval($_POST['tipo_documento_id']) : 0;

$sql = "
    SELECT 
        id,
        version,
        archivo,
        DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:%s') AS fecha_subida,
        DATE_FORMAT(fecha_vencimiento, '%Y-%m-%d') AS fecha_vencimiento,
        is_current
    FROM documentos
    WHERE entidad_tipo = 'vehiculo'
      AND entidad_id = ?
      AND tipo_documento_id = ?
      AND eliminado = 0
    ORDER BY version DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $vehiculo_id, $tipo_documento_id);
$stmt->execute();

$stmt->bind_result($id, $version, $archivo, $fecha_subida, $fecha_vencimiento, $is_current);

$historial = array();

while ($stmt->fetch()) {
    $historial[] = array(
        "id"                => $id,
        "version"           => $version,
        "archivo"           => $archivo,
        "ruta"              => "/uploads/documentos/vehiculos/" . $archivo,
        "fecha_subida"      => $fecha_subida,
        "fecha_vencimiento" => $fecha_vencimiento,
        "is_current"        => intval($is_current)
    );
}

echo json_encode(array(
    "ok" => true,
    "historial" => $historial
));

exit;
