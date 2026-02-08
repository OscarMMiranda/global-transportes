<?php
// archivo: /modulos/documentos_empresas/acciones/listar_historial_empresa.php

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

header('Content-Type: application/json; charset=utf-8');

if (function_exists('ob_clean')) { ob_clean(); }

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

$empresa_id        = isset($_POST['empresa_id']) ? intval($_POST['empresa_id']) : 0;
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
    WHERE entidad_tipo = 'empresa'
      AND entidad_id = ?
      AND tipo_documento_id = ?
      AND eliminado = 0
    ORDER BY version DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $empresa_id, $tipo_documento_id);
$stmt->execute();

$stmt->bind_result($id, $version, $archivo, $fecha_subida, $fecha_vencimiento, $is_current);

$historial = [];

while ($stmt->fetch()) {
    $historial[] = [
        "id"                => $id,
        "version"           => $version,
        "archivo"           => $archivo,
        "ruta"              => "/uploads/documentos/empresas/" . $archivo,
        "fecha_subida"      => $fecha_subida,
        "fecha_vencimiento" => $fecha_vencimiento,
        "is_current"        => intval($is_current)
    ];
}

echo json_encode([
    "ok" => true,
    "historial" => $historial
]);

exit;
