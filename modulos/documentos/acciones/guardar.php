<?php
// archivo: /modulos/documentos/acciones/guardar.php

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

// ===============================
// VALIDAR DATOS
// ===============================
$tipo_documento_id = isset($_POST['tipo_documento_id']) ? intval($_POST['tipo_documento_id']) : 0;
$numero            = isset($_POST['numero']) ? trim($_POST['numero']) : '';
$entidad_tipo      = isset($_POST['entidad_tipo']) ? trim($_POST['entidad_tipo']) : '';
$entidad_id        = isset($_POST['entidad_id']) ? intval($_POST['entidad_id']) : 0;
$fecha_inicio      = isset($_POST['fecha_inicio']) && $_POST['fecha_inicio'] !== '' ? $_POST['fecha_inicio'] : null;
$fecha_vencimiento = isset($_POST['fecha_vencimiento']) ? $_POST['fecha_vencimiento'] : '';
$alertar_dias_antes = isset($_POST['alertar_dias_antes']) ? intval($_POST['alertar_dias_antes']) : 30;
$uploaded_by       = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 0;

if ($tipo_documento_id <= 0 || $numero === '' || $entidad_tipo === '' || $entidad_id <= 0 || $fecha_vencimiento === '') {
    echo json_encode(array("success" => false, "error" => "Faltan datos obligatorios."));
    exit;
}

// ===============================
// VALIDAR ARCHIVO
// ===============================
if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(array("success" => false, "error" => "Archivo no recibido o con error."));
    exit;
}

$archivoTmp  = $_FILES['archivo']['tmp_name'];
$nombreOrig  = $_FILES['archivo']['name'];
$ext         = pathinfo($nombreOrig, PATHINFO_EXTENSION);

// ===============================
// CREAR RUTA DESTINO
// ===============================
$baseUploads = $_SERVER['DOCUMENT_ROOT'] . '/uploads/documentos/';
$dirEntidad  = $baseUploads . $entidad_tipo . '/' . $entidad_id . '/';

if (!is_dir($dirEntidad)) {
    mkdir($dirEntidad, 0775, true);
}

// Nombre estandarizado
$nombreLimpio = preg_replace('/[^a-zA-Z0-9_\-]/', '_', strtolower($numero));
$nombreFinal  = $nombreLimpio . '_' . date('YmdHis') . '.' . $ext;
$rutaFinal    = $dirEntidad . $nombreFinal;

// ===============================
// MOVER ARCHIVO
// ===============================
if (!move_uploaded_file($archivoTmp, $rutaFinal)) {
    echo json_encode(array("success" => false, "error" => "No se pudo mover el archivo al destino."));
    exit;
}

$rutaRelativa = '/uploads/documentos/' . $entidad_tipo . '/' . $entidad_id . '/' . $nombreFinal;

// ===============================
// INSERTAR EN BD
// ===============================
$sql = "
    INSERT INTO documentos (
        tipo_documento_id,
        numero,
        entidad_tipo,
        entidad_id,
        archivo,
        fecha_inicio,
        fecha_vencimiento,
        alertar_dias_antes,
        uploaded_by,
        created_at,
        eliminado
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 0)
";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ississiii",
    $tipo_documento_id,
    $numero,
    $entidad_tipo,
    $entidad_id,
    $rutaRelativa,
    $fecha_inicio,
    $fecha_vencimiento,
    $alertar_dias_antes,
    $uploaded_by
);

if (!$stmt->execute()) {
    echo json_encode(array("success" => false, "error" => "Error al guardar en base de datos."));
    exit;
}

echo json_encode(array("success" => true));
exit;
