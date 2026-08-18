<?php
// archivo: modulos/papeletas/acciones/eliminar_archivo.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

// incluir función de log
require_once $_SERVER['DOCUMENT_ROOT'] . '/modulos/papeletas/acciones/log_registrar.php';

$id = intval($_POST['id']);

if ($id <= 0) {
    echo json_encode(["ok" => false, "msg" => "ID inválido"]);
    exit;
}

// Obtener datos del archivo antes de eliminarlo
$sqlArchivo = "
    SELECT papeleta_id, archivo
    FROM papeleta_archivos
    WHERE id = $id
    LIMIT 1
";

$resArchivo = mysqli_query($conn, $sqlArchivo);

if (!$resArchivo || mysqli_num_rows($resArchivo) == 0) {
    echo json_encode(["ok" => false, "msg" => "Archivo no encontrado"]);
    exit;
}

$archivoData = mysqli_fetch_assoc($resArchivo);
$papeleta_id = intval($archivoData['papeleta_id']);
$nombreArchivo = $archivoData['archivo'];

// Marcar como eliminado
$sql = "
    UPDATE papeleta_archivos
    SET deleted_at = NOW()
    WHERE id = $id
";

if (!mysqli_query($conn, $sql)) {
    echo json_encode(["ok" => false, "msg" => "Error SQL: " . mysqli_error($conn)]);
    exit;
}

// Registrar LOG corporativo
registrarLog(
    $papeleta_id,
    'archivo_eliminado',
    "Archivo eliminado: $nombreArchivo"
);

echo json_encode(["ok" => true]);
exit;
?>
