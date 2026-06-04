<?php
// archivo: modulos/vehiculos/acciones/actualizar_descripcion_foto.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

$idFoto = isset($_POST['id_foto']) ? intval($_POST['id_foto']) : 0;
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';

if ($idFoto <= 0) {
    echo json_encode([
        "success" => false,
        "msg" => "ID de foto inválido"
    ]);
    exit;
}

$descripcionEsc = $conn->real_escape_string($descripcion);

$sql = "UPDATE vehiculo_fotos SET descripcion = " . ($descripcionEsc === '' ? "NULL" : "'$descripcionEsc'") . " WHERE id_foto = $idFoto";

if ($conn->query($sql)) {
    echo json_encode([
        "success" => true,
        "msg" => "Descripción actualizada correctamente"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "msg" => "No se pudo actualizar la descripción"
    ]);
}
