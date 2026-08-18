<?php
// archivo: /modulos/papeletas/acciones/guardar.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

ini_set('display_errors', 0);

// incluir log
require_once $_SERVER['DOCUMENT_ROOT'] . '/modulos/papeletas/acciones/log_registrar.php';

/* ============================================================
   VALIDAR CAMPOS PRINCIPALES
   ============================================================ */

$vehiculo_id        = intval($_POST['vehiculo_id']);
$conductor_id       = ($_POST['conductor_id'] === "" ? "NULL" : intval($_POST['conductor_id']));
$entidad_emisora_id = intval($_POST['entidad_emisora_id']);
$infraccion_id      = intval($_POST['infraccion_id']);

$fecha_infraccion   = mysqli_real_escape_string($conn, $_POST['fecha_infraccion']);
$fecha_notificacion = ($_POST['fecha_notificacion'] === "" ? "NULL" : "'" . mysqli_real_escape_string($conn, $_POST['fecha_notificacion']) . "'");
$fecha_vencimiento  = ($_POST['fecha_vencimiento'] === "" ? "NULL" : "'" . mysqli_real_escape_string($conn, $_POST['fecha_vencimiento']) . "'");

$lugar              = mysqli_real_escape_string($conn, $_POST['lugar']);
$descripcion        = mysqli_real_escape_string($conn, $_POST['descripcion']);

$codigo_papeleta    = mysqli_real_escape_string($conn, $_POST['codigo_papeleta']);

/* ============================================================
   VALIDAR CÓDIGO DE PAPELETA DUPLICADO
   ============================================================ */

$sqlCheck = "
    SELECT id 
    FROM papeletas 
    WHERE codigo_papeleta = '$codigo_papeleta'
    LIMIT 1
";

$resCheck = mysqli_query($conn, $sqlCheck);

if (mysqli_num_rows($resCheck) > 0) {
    echo json_encode([
        'ok' => false,
        'msg' => 'El código de papeleta ya está registrado.'
    ]);
    exit;
}

/* ============================================================
   OBTENER MONTO DE LA INFRACCIÓN
   ============================================================ */

$sqlMonto = "SELECT monto_base FROM infracciones WHERE id = $infraccion_id LIMIT 1";
$resMonto = mysqli_query($conn, $sqlMonto);

if (!$resMonto || mysqli_num_rows($resMonto) == 0) {
    echo json_encode(['ok' => false, 'msg' => 'No se encontró el monto de la infracción']);
    exit;
}

$rowMonto = mysqli_fetch_assoc($resMonto);
$monto = floatval($rowMonto['monto_base']);

/* ============================================================
   INSERTAR PAPELETA
   ============================================================ */

$sql = "
INSERT INTO papeletas
(
    vehiculo_id,
    conductor_id,
    entidad_emisora_id,
    infraccion_id,
    fecha_infraccion,
    fecha_notificacion,
    fecha_vencimiento,
    lugar,
    descripcion,
    codigo_papeleta,
    monto,
    monto_descuento,
    monto_pagado,
    estado_id,
    created_at
)
VALUES
(
    $vehiculo_id,
    $conductor_id,
    $entidad_emisora_id,
    $infraccion_id,
    '$fecha_infraccion',
    $fecha_notificacion,
    $fecha_vencimiento,
    '$lugar',
    '$descripcion',
    '$codigo_papeleta',
    $monto,
    0.00,
    0.00,
    1,
    NOW()
)
";

if (!mysqli_query($conn, $sql)) {
    echo json_encode(['ok' => false, 'msg' => mysqli_error($conn)]);
    exit;
}

$papeleta_id = mysqli_insert_id($conn);

/* ============================================================
   REGISTRAR LOG DE CREACIÓN
   ============================================================ */

registrarLog(
    $papeleta_id,
    'papeleta_creada',
    "Código: $codigo_papeleta, Vehículo: $vehiculo_id, Infracción: $infraccion_id"
);

/* ============================================================
   SUBIR ARCHIVO (SI EXISTE)
   ============================================================ */

if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {

    $archivo = $_FILES['archivo'];
    $descripcion_archivo = mysqli_real_escape_string($conn, $_POST['descripcion_archivo']);

    $nombre = time() . "_" . basename($archivo['name']);
    $ruta_relativa = "/uploads/papeletas/" . $nombre;
    $ruta_fisica = $_SERVER['DOCUMENT_ROOT'] . $ruta_relativa;

    if (move_uploaded_file($archivo['tmp_name'], $ruta_fisica)) {

        $sqlArchivo = "
        INSERT INTO papeleta_archivos
        (papeleta_id, archivo, descripcion, created_at)
        VALUES
        ($papeleta_id, '$nombre', '$descripcion_archivo', NOW())
        ";

        mysqli_query($conn, $sqlArchivo);

        // registrar log del archivo subido
        registrarLog(
            $papeleta_id,
            'archivo_subido',
            "Archivo: $nombre"
        );
    }
}

/* ============================================================
   RESPUESTA FINAL
   ============================================================ */

echo json_encode(['ok' => true]);
exit;
?>
