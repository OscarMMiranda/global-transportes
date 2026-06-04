<?php
// archivo: modulos/vehiculos/acciones/eliminar_foto.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

$idFoto = isset($_POST['id_foto']) ? intval($_POST['id_foto']) : 0;

if ($idFoto <= 0) {
    echo json_encode([
        "success" => false,
        "msg" => "ID de foto inválido"
    ]);
    exit;
}

// obtener ruta
$sql = "SELECT ruta_archivo FROM vehiculo_fotos WHERE id_foto = $idFoto";
$res = $conn->query($sql);

if (!$res || $res->num_rows == 0) {
    echo json_encode([
        "success" => false,
        "msg" => "La foto no existe"
    ]);
    exit;
}

$row = $res->fetch_assoc();
$rutaPublica = $row['ruta_archivo'];

// borrar registro
$sqlDel = "DELETE FROM vehiculo_fotos WHERE id_foto = $idFoto";
if ($conn->query($sqlDel)) {

    // intentar borrar archivo físico
    if ($rutaPublica) {
        $rutaFisica = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($rutaPublica, '/');
        if (file_exists($rutaFisica)) {
            @unlink($rutaFisica);
        }
    }

    echo json_encode([
        "success" => true,
        "msg" => "Foto eliminada correctamente"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "msg" => "No se pudo eliminar la foto"
    ]);
}
