<?php
// archivo: /modulos/papeletas/acciones/subir_archivo.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

// incluir función de log
require_once $_SERVER['DOCUMENT_ROOT'] . '/modulos/papeletas/acciones/log_registrar.php';

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode([
        "success" => false,
        "msg" => "ID de papeleta inválido."
    ]);
    exit;
}

if (!isset($_FILES['archivo'])) {
    echo json_encode([
        "success" => false,
        "msg" => "No se recibió ningún archivo."
    ]);
    exit;
}

$archivo = $_FILES['archivo'];

if ($archivo['error'] !== UPLOAD_ERR_OK) {
    echo json_encode([
        "success" => false,
        "msg" => "Error al subir el archivo."
    ]);
    exit;
}

$permitidos = array("pdf", "jpg", "jpeg", "png");
$ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $permitidos)) {
    echo json_encode([
        "success" => false,
        "msg" => "Formato no permitido. Solo PDF, JPG, JPEG, PNG."
    ]);
    exit;
}

// Carpeta de destino
$carpeta = $_SERVER['DOCUMENT_ROOT'] . "/uploads/papeletas/";

if (!is_dir($carpeta)) {
    mkdir($carpeta, 0777, true);
}

// Nombre corporativo del archivo
$nombreFinal = "papeleta_" . $id . "_" . time() . "." . $ext;
$rutaFinal = $carpeta . $nombreFinal;

if (!move_uploaded_file($archivo['tmp_name'], $rutaFinal)) {
    echo json_encode([
        "success" => false,
        "msg" => "No se pudo guardar el archivo."
    ]);
    exit;
}

// Descripción del archivo
$descripcion = isset($_POST['descripcion_archivo'])
    ? mysqli_real_escape_string($conn, $_POST['descripcion_archivo'])
    : "";

// Registrar archivo en BD
$sql = "
    INSERT INTO papeleta_archivos (papeleta_id, archivo, descripcion, created_at)
    VALUES ($id, '$nombreFinal', '$descripcion', NOW())
";

if (!mysqli_query($conn, $sql)) {
    echo json_encode([
        "success" => false,
        "msg" => "Error SQL al registrar archivo: " . mysqli_error($conn)
    ]);
    exit;
}

// Registrar LOG de acción interna
registrarLog(
    $id,
    'archivo_subido',
    "Archivo: $nombreFinal"
);

echo json_encode([
    "success" => true,
    "msg" => "Archivo subido correctamente."
]);
exit;
?>
