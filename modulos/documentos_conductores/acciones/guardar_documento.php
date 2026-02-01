<?php
// archivo: /modulos/documentos_conductores/acciones/guardar_documento.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

$conductorId = intval($_POST['conductor_id']);
$tipoId      = intval($_POST['tipo_documento_id']);
$fechaVenc   = $_POST['fecha_vencimiento'];

// Validación básica
if ($conductorId <= 0 || $tipoId <= 0) {
    echo json_encode(["ok" => false, "mensaje" => "Datos incompletos"]);
    exit;
}

// Validación archivo
if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["ok" => false, "mensaje" => "Archivo no recibido"]);
    exit;
}

// Tamaño máximo 10 MB
if ($_FILES['archivo']['size'] > 10 * 1024 * 1024) {
    echo json_encode(["ok" => false, "mensaje" => "El archivo supera los 10 MB"]);
    exit;
}

// MIME permitido
$mime = $_FILES['archivo']['type'];
$permitidos = ["application/pdf", "image/jpeg", "image/png", "image/webp"];

if (!in_array($mime, $permitidos)) {
    echo json_encode(["ok" => false, "mensaje" => "Tipo de archivo no permitido"]);
    exit;
}

$archivoTmp = $_FILES['archivo']['tmp_name'];
$nombreOrig = $_FILES['archivo']['name'];
$ext = strtolower(pathinfo($nombreOrig, PATHINFO_EXTENSION));

// Carpeta destino
$carpeta = $_SERVER['DOCUMENT_ROOT'] . '/uploads/documentos/conductores/';
if (!is_dir($carpeta)) mkdir($carpeta, 0775, true);

$nombreFinal = "cond_{$conductorId}_tipo_{$tipoId}_" . time() . "." . $ext;

// Mover archivo
if (!move_uploaded_file($archivoTmp, $carpeta . $nombreFinal)) {
    echo json_encode(["ok" => false, "mensaje" => "No se pudo guardar el archivo en el servidor"]);
    exit;
}

// Hash del archivo
$hash = hash_file('sha256', $carpeta . $nombreFinal);

// Normalizar fecha (MISMO COMPORTAMIENTO QUE VEHÍCULOS)
if ($fechaVenc === "" || $fechaVenc === null) {
    $fechaVenc = "0000-00-00"; // evita el 500
}

// Obtener versión actual
$sqlVer = "
    SELECT version 
    FROM documentos 
    WHERE entidad_tipo='conductor' 
      AND entidad_id=? 
      AND tipo_documento_id=? 
      AND is_current=1 
      AND eliminado=0
    ORDER BY id DESC LIMIT 1
";

$stmtVer = $conn->prepare($sqlVer);
$stmtVer->bind_param("ii", $conductorId, $tipoId);
$stmtVer->execute();
$resVer = $stmtVer->get_result();
$rowVer = $resVer->fetch_assoc();

$versionNueva = $rowVer ? intval($rowVer['version']) + 1 : 1;

// Marcar versión anterior como no actual
if ($rowVer) {
    $conn->query("
        UPDATE documentos 
        SET is_current=0 
        WHERE entidad_tipo='conductor' 
          AND entidad_id=$conductorId 
          AND tipo_documento_id=$tipoId 
          AND is_current=1
    ");
}

// INSERT versión nueva
$stmt = $conn->prepare("
    INSERT INTO documentos (
        tipo_documento_id, numero, entidad_tipo, entidad_id,
        archivo, fecha_inicio, fecha_vencimiento,
        alertar_dias_antes, uploaded_by, visibilidad_roles,
        created_at, eliminado, archivo_hash, archivo_mime,
        archivo_tamano, version, is_current
    ) VALUES (
        ?, '', 'conductor', ?, ?, NULL, ?, 30, 1, NULL,
        NOW(), 0, ?, ?, ?, ?, 1
    )
");

$stmt->bind_param(
    "iissssii",
    $tipoId,
    $conductorId,
    $nombreFinal,
    $fechaVenc,
    $hash,
    $mime,
    $_FILES['archivo']['size'],
    $versionNueva
);

$ok = $stmt->execute();

if (!$ok) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Error BD: " . $stmt->error
    ]);
    exit;
}

echo json_encode([
    "ok" => true,
    "mensaje" => "Documento guardado correctamente"
]);
