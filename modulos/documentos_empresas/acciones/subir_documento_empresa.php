<?php

file_put_contents(__DIR__ . "/AAA_LOCAL.txt", "EJECUTADO\n", FILE_APPEND);


// archivo: /modulos/documentos_empresas/acciones/subir_documento_empresa.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../../includes/config.php';

// LOG DE EJECUCIÓN
file_put_contents(__DIR__ . "/AAA_TEST.txt", date("Y-m-d H:i:s") . " EJECUTANDO\n", FILE_APPEND);

header('Content-Type: application/json');

// ============================================================
// VALIDAR PARAMETROS
// ============================================================
if (
    !isset($_POST['tipo_documento_id']) ||
    !isset($_POST['empresa_id']) ||
    !is_numeric($_POST['tipo_documento_id']) ||
    !is_numeric($_POST['empresa_id'])
) {
    echo json_encode(array("success" => false, "message" => "Parámetros inválidos"));
    exit;
}

$tipo_documento_id = intval($_POST['tipo_documento_id']);
$empresa_id        = intval($_POST['empresa_id']);

$numero            = isset($_POST['numero']) ? $_POST['numero'] : "";
$fecha_inicio      = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : "";
$fecha_vencimiento = isset($_POST['fecha_vencimiento']) ? $_POST['fecha_vencimiento'] : "";

// Normalizar valores NULL para PHP 5.6
$numero            = $numero ?: "";
$fecha_inicio      = $fecha_inicio ?: "";
$fecha_vencimiento = $fecha_vencimiento ?: "";

// ============================================================
// VALIDAR ARCHIVO
// ============================================================
if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(array("success" => false, "message" => "Archivo no recibido"));
    exit;
}

$archivo_tmp  = $_FILES['archivo']['tmp_name'];
$archivo_name = basename($_FILES['archivo']['name']);
$ext          = strtolower(pathinfo($archivo_name, PATHINFO_EXTENSION));

$permitidos = array('pdf', 'jpg', 'jpeg', 'png');

if (!in_array($ext, $permitidos)) {
    echo json_encode(array("success" => false, "message" => "Formato no permitido"));
    exit;
}

$conn = getConnection();

// ============================================================
// OBTENER VERSION ACTUAL
// ============================================================
$sql = $conn->prepare("
    SELECT id, version
    FROM documentos
    WHERE entidad_tipo = 'empresa'
      AND entidad_id = ?
      AND tipo_documento_id = ?
      AND is_current = 1
      AND eliminado = 0
    LIMIT 1
");

$sql->bind_param("ii", $empresa_id, $tipo_documento_id);
$sql->execute();
$res = $sql->get_result();

$version_actual = 0;
$documento_anterior_id = null;

if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $version_actual = intval($row['version']);
    $documento_anterior_id = intval($row['id']);
}

$nueva_version = $version_actual + 1;

// ============================================================
// RUTA FINAL
// ============================================================
$carpeta = __DIR__ . "/../../../uploads/documentos/empresas/";

if (!is_dir($carpeta)) {
    mkdir($carpeta, 0777, true);
}

$nombre_final = "empresa_" . $empresa_id . "_tipo_" . $tipo_documento_id . "_v" . $nueva_version . "." . $ext;
$ruta_destino = $carpeta . $nombre_final;

if (!move_uploaded_file($archivo_tmp, $ruta_destino)) {
    echo json_encode(array("success" => false, "message" => "Error guardando archivo"));
    exit;
}

// ============================================================
// DESACTIVAR VERSION ANTERIOR
// ============================================================
if ($documento_anterior_id) {
    $conn->query("UPDATE documentos SET is_current = 0 WHERE id = " . $documento_anterior_id);
}

// ============================================================
// INSERTAR NUEVO DOCUMENTO (INSERT EXPLÍCITO Y SEGURO)
// ============================================================

// uploaded_by es obligatorio y NO tiene default
$uploaded_by = 1; // Ajusta según tu sistema de login

$sql = $conn->prepare("
    INSERT INTO documentos (
        tipo_documento_id,
        numero,
        entidad_tipo,
        entidad_id,
        archivo,
        fecha_inicio,
        fecha_vencimiento,
        version,
        is_current,
        eliminado,
        uploaded_by
    ) VALUES (
        ?,
        ?,
        'empresa',
        ?,
        ?,
        ?,
        ?,
        ?,
        1,
        0,
        ?
    )
");

if (!$sql) {
    file_put_contents(__DIR__ . "/AAA_SQL_ERROR.txt", "ERROR PREPARE: " . $conn->error . "\n", FILE_APPEND);
    echo json_encode(["success" => false, "message" => "Error en prepare"]);
    exit;
}

$sql->bind_param(
    "isssssii",
    $tipo_documento_id,
    $numero,
    $empresa_id,
    $nombre_final,
    $fecha_inicio,
    $fecha_vencimiento,
    $nueva_version,
    $uploaded_by
);

if (!$sql->execute()) {
    file_put_contents(__DIR__ . "/AAA_SQL_ERROR.txt", "ERROR EXECUTE: " . $sql->error . "\n", FILE_APPEND);
    echo json_encode(array("success" => false, "message" => "Error insertando documento"));
    exit;
}

// ============================================================
// RESPUESTA FINAL
// ============================================================
echo json_encode(array("success" => true));
exit;
