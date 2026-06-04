<?php
// archivo: /modulos/vehiculos/acciones/agregar_foto.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../../includes/config.php';
require_once INCLUDES_PATH . '/funciones.php';

header('Content-Type: application/json');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$conn = getConnection();

// ---------------------------------------------------------
// VALIDAR ID
// ---------------------------------------------------------
$idVehiculo = isset($_POST['foto_id_vehiculo']) ? intval($_POST['foto_id_vehiculo']) : 0;

if ($idVehiculo <= 0) {
    echo json_encode(array(
        'success' => false,
        'msg' => 'ID de vehículo inválido'
    ));
    exit;
}

// ---------------------------------------------------------
// VALIDAR ARCHIVO
// ---------------------------------------------------------
if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(array(
        'success' => false,
        'msg' => 'No se recibió ninguna imagen válida'
    ));
    exit;
}

$archivo = $_FILES['foto'];
$nombreOriginal = $archivo['name'];
$ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

$permitidos = array('jpg', 'jpeg', 'png', 'webp');

if (!in_array($ext, $permitidos)) {
    echo json_encode(array(
        'success' => false,
        'msg' => 'Formato no permitido. Solo JPG, PNG o WEBP'
    ));
    exit;
}

// ---------------------------------------------------------
// GENERAR NOMBRE SEGURO
// ---------------------------------------------------------
$nombreNuevo = "vehiculo_" . $idVehiculo . "_" . time() . "." . $ext;

$destinoCarpeta = __DIR__ . "/../../../uploads/vehiculos/fotos/";

if (!is_dir($destinoCarpeta)) {
    mkdir($destinoCarpeta, 0777, true);
}

$rutaFinal = $destinoCarpeta . $nombreNuevo;

if (!move_uploaded_file($archivo['tmp_name'], $rutaFinal)) {
    echo json_encode(array(
        'success' => false,
        'msg' => 'No se pudo guardar la imagen en el servidor'
    ));
    exit;
}

$rutaPublica = "/uploads/vehiculos/fotos/" . $nombreNuevo;

// ---------------------------------------------------------
// DESCRIPCIÓN (OPCIONAL)
// ---------------------------------------------------------
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
if ($descripcion === '') {
    $descripcion = null; // para que vaya NULL a la BD si está vacío
}

// ---------------------------------------------------------
// INSERT CON DESCRIPCIÓN
// ---------------------------------------------------------
$creadoPor = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 0;

$sql = "
    INSERT INTO vehiculo_fotos 
    (id_vehiculo, ruta_archivo, descripcion, creado_en, creado_por)
    VALUES (?, ?, ?, NOW(), ?)
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("issi", $idVehiculo, $rutaPublica, $descripcion, $creadoPor);

if ($stmt->execute()) {
    echo json_encode(array(
        'success' => true,
        'msg' => 'Foto agregada correctamente'
    ));
} else {
    echo json_encode(array(
        'success' => false,
        'msg' => 'Error SQL: ' . $stmt->error
    ));
}
