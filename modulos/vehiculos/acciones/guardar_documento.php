<?php
// archivo: /modulos/vehiculos/acciones/guardar_documento.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

$entidad_tipo = $_POST['entidad_tipo']; // 'vehiculo'
$entidad_id   = intval($_POST['entidad_id']);
$tipo_documento_id = intval($_POST['tipo_documento_id']);
$numero = $conn->real_escape_string($_POST['numero']);
$fecha_inicio = $_POST['fecha_inicio'] ?: NULL;
$fecha_vencimiento = $_POST['fecha_vencimiento'] ?: NULL;
$observaciones = $conn->real_escape_string($_POST['observaciones']);
$uploaded_by = $_SESSION['usuario_id']; // usuario logueado

// ------------------------------------------------------------
// SUBIR ARCHIVO
// ------------------------------------------------------------
$archivo = null;

if (!empty($_FILES['archivo']['name'])) {

    $nombre = time() . "_" . basename($_FILES['archivo']['name']);
    $ruta = $_SERVER['DOCUMENT_ROOT'] . "/uploads/documentos/vehiculos/" . $nombre;

    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta)) {
        $archivo = $nombre;
    } else {
        echo json_encode(["success" => false, "message" => "Error al subir archivo"]);
        exit;
    }
}

// ------------------------------------------------------------
// VERSIONADO CORPORATIVO
// ------------------------------------------------------------

// 1) Marcar versiones anteriores como históricas
$sql_old = "
    UPDATE documentos
    SET is_current = 0
    WHERE entidad_tipo = '$entidad_tipo'
      AND entidad_id = $entidad_id
      AND tipo_documento_id = $tipo_documento_id
      AND eliminado = 0
";
$conn->query($sql_old);

// 2) Obtener última versión
$sql_ver = "
    SELECT MAX(version) AS v
    FROM documentos
    WHERE entidad_tipo = '$entidad_tipo'
      AND entidad_id = $entidad_id
      AND tipo_documento_id = $tipo_documento_id
";
$res_ver = $conn->query($sql_ver);
$row_ver = $res_ver->fetch_assoc();
$version = $row_ver['v'] ? intval($row_ver['v']) + 1 : 1;

// ------------------------------------------------------------
// INSERTAR NUEVA VERSIÓN (VIGENTE)
// ------------------------------------------------------------
$sql = "
    INSERT INTO documentos (
        entidad_tipo,
        entidad_id,
        tipo_documento_id,
        numero,
        fecha_inicio,
        fecha_vencimiento,
        archivo,
        uploaded_by,
        observaciones,
        created_at,
        eliminado,
        version,
        is_current
    ) VALUES (
        '$entidad_tipo',
        $entidad_id,
        $tipo_documento_id,
        '$numero',
        " . ($fecha_inicio ? "'$fecha_inicio'" : "NULL") . ",
        " . ($fecha_vencimiento ? "'$fecha_vencimiento'" : "NULL") . ",
        " . ($archivo ? "'$archivo'" : "NULL") . ",
        $uploaded_by,
        '$observaciones',
        NOW(),
        0,
        $version,
        1
    )
";

if ($conn->query($sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $conn->error]);
}
