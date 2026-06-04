<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

header("Content-Type: application/json");

// VALIDAR ID
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(array("ok" => false, "msg" => "ID inválido"));
    exit;
}

$id = intval($_POST['id']);

// LIMPIAR DATOS
function v($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : "";
}

$sql = "
    UPDATE vehiculos SET
        estado_id = '" . intval(v('estado_id')) . "',
        kilometraje = '" . intval(v('kilometraje')) . "',
        horas_motor = '" . intval(v('horas_motor')) . "',
        prox_mantenimiento = '" . intval(v('prox_mantenimiento')) . "',
        centro_costo_id = " . (v('centro_costo_id') !== "" ? intval(v('centro_costo_id')) : "NULL") . ",
        observaciones = '" . $conn->real_escape_string(v('observaciones')) . "'
    WHERE id = $id
";

if ($conn->query($sql)) {
    echo json_encode(array("ok" => true));
} else {
    echo json_encode(array("ok" => false, "msg" => $conn->error));
}
