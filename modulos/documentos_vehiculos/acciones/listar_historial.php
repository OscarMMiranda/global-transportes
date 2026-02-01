<?php
    // archivo: /modulos/documentos_vehiculos/acciones/listar_historial.php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

$vehiculoId = intval($_POST['vehiculo_id']);
$tipoId     = intval($_POST['tipo_documento_id']);

$sql = "
    SELECT 
        id, archivo, fecha_vencimiento, version, is_current, created_at
    FROM documentos
    WHERE entidad_tipo='vehiculo'
      AND entidad_id=$vehiculoId
      AND tipo_documento_id=$tipoId
      AND eliminado=0
    ORDER BY version DESC
";

$res = $conn->query($sql);

$historial = [];
while ($row = $res->fetch_assoc()) {
    $row['ruta'] = "/uploads/documentos/vehiculos/" . $row['archivo'];
    $historial[] = $row;
}

echo json_encode(["ok" => true, "historial" => $historial]);
