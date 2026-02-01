<?php
// archivo: /modulos/documentos_conductores/acciones/listar_documentos.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

$conductorId = isset($_POST['conductor_id']) ? intval($_POST['conductor_id']) : 0;

$documentos = [];

// 1) Traer configuración de documentos para CONDUCTOR
$sqlConfig = "
    SELECT 
        dc.tipo_documento_id,
        dc.obligatorio,
        dc.orden,
        td.nombre AS nombre_documento
    FROM documentos_config dc
    INNER JOIN tipos_documento td ON td.id = dc.tipo_documento_id
    WHERE dc.entidad_tipo = 'conductor'
    ORDER BY dc.orden ASC
";

$resConfig = $conn->query($sqlConfig);

while ($cfg = $resConfig->fetch_assoc()) {

    $tipoId = intval($cfg['tipo_documento_id']);

    // 2) Buscar el documento más reciente del conductor
    $sqlDoc = "
        SELECT archivo, fecha_vencimiento
        FROM documentos
        WHERE entidad_tipo='conductor'
        AND entidad_id=$conductorId
        AND tipo_documento_id=$tipoId
        AND eliminado=0
        ORDER BY fecha_vencimiento DESC
        LIMIT 1
    ";

    $resDoc = $conn->query($sqlDoc);
    $doc = $resDoc->fetch_assoc();

    // 3) Determinar estado
    $estado = "No cargado";

    if ($doc) {
        if (!empty($doc['fecha_vencimiento'])) {
            $fv = strtotime($doc['fecha_vencimiento']);
            $hoy = strtotime(date('Y-m-d'));

            if ($fv < $hoy) {
                $estado = "Vencido";
            } else {
                $estado = "OK";
            }
        } else {
            $estado = "OK";
        }
    }

    // 4) Ruta del archivo
    $ruta = $doc ? "/uploads/documentos/conductores/" . $doc['archivo'] : null;

    // 5) Armar respuesta
    $documentos[] = [
        "tipo_documento_id" => $tipoId,
        "descripcion" => utf8_encode($cfg['nombre_documento']),
        "obligatorio" => intval($cfg['obligatorio']),
        "fecha_vencimiento" => $doc ? $doc['fecha_vencimiento'] : null,
        "estado" => $estado,
        "archivo" => $doc ? $doc['archivo'] : null,
        "ruta" => $ruta
    ];
}

echo json_encode([
    "ok" => true,
    "documentos" => $documentos
]);
