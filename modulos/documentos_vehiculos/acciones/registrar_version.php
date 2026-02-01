<?php
// ======================================================
//  archivo: registrar_version.php
//  RESPONSABILIDAD: registrar una nueva versión del documento
//  COMPATIBLE: PHP 5.6 + IIS
// ======================================================

require_once __DIR__ . '/../../../config/conexion.php';

$vehiculo_id = isset($_POST['vehiculo_id']) ? intval($_POST['vehiculo_id']) : 0;
$tipo_documento_id = isset($_POST['tipo_documento_id']) ? intval($_POST['tipo_documento_id']) : 0;
$archivo = isset($_POST['archivo']) ? $_POST['archivo'] : '';
$ruta = isset($_POST['ruta']) ? $_POST['ruta'] : '';
$fecha_vencimiento = isset($_POST['fecha_vencimiento']) ? $_POST['fecha_vencimiento'] : null;

if ($vehiculo_id <= 0 || $tipo_documento_id <= 0 || $archivo == '' || $ruta == '') {
    echo json_encode(array('ok' => false, 'mensaje' => 'Parámetros incompletos'));
    exit;
}

// Obtener la última versión
$sqlVersion = "
    SELECT MAX(version) AS ultima
    FROM documentos_vehiculos
    WHERE vehiculo_id = $vehiculo_id
      AND tipo_documento_id = $tipo_documento_id
";

$res = $conexion->query($sqlVersion);
$row = $res->fetch_assoc();
$ultima = $row['ultima'] ? intval($row['ultima']) : 0;
$nuevaVersion = $ultima + 1;

// Poner todas las versiones en 0
$sqlReset = "
    UPDATE documentos_vehiculos
    SET is_current = 0
    WHERE vehiculo_id = $vehiculo_id
      AND tipo_documento_id = $tipo_documento_id
";
$conexion->query($sqlReset);

// Insertar nueva versión
$sqlInsert = "
    INSERT INTO documentos_vehiculos
    (vehiculo_id, tipo_documento_id, archivo, ruta, fecha_subida, fecha_vencimiento, version, is_current)
    VALUES
    ($vehiculo_id, $tipo_documento_id, '$archivo', '$ruta', NOW(), " . 
    ($fecha_vencimiento ? "'$fecha_vencimiento'" : "NULL") . ",
    $nuevaVersion, 1)
";

$ok = $conexion->query($sqlInsert);

echo json_encode(array(
    'ok' => $ok ? true : false,
    'mensaje' => $ok ? 'Versión registrada correctamente' : 'Error al registrar versión'
));
