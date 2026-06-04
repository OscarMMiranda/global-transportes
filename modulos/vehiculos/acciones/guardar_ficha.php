<?php
    // archivo: modulos/vehiculos/acciones/guardar_ficha.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

header("Content-Type: application/json");

// VALIDAR ID
if (!isset($_POST['vehiculo_id']) || !is_numeric($_POST['vehiculo_id'])) {
    echo json_encode(array("success" => false, "msg" => "ID inválido"));
    exit;
}

$id = intval($_POST['vehiculo_id']);

// LIMPIAR DATOS
function v($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : "";
}

// VERIFICAR SI EXISTE REGISTRO
$check = $conn->query("SELECT id FROM vehiculo_ficha_tecnica WHERE vehiculo_id = $id LIMIT 1");

if ($check && $check->num_rows > 0) {

    // ============================
    // UPDATE
    // ============================
    $sql = "
        UPDATE vehiculo_ficha_tecnica SET
            motor_marca = '" . $conn->real_escape_string(v('motor_marca')) . "',
            motor_modelo = '" . $conn->real_escape_string(v('motor_modelo')) . "',
            motor_serie = '" . $conn->real_escape_string(v('motor_serie')) . "',
            cilindrada = '" . $conn->real_escape_string(v('cilindrada')) . "',
            potencia_hp = '" . $conn->real_escape_string(v('potencia_hp')) . "',
            chasis_marca = '" . $conn->real_escape_string(v('chasis_marca')) . "',
            chasis_modelo = '" . $conn->real_escape_string(v('chasis_modelo')) . "',
            chasis_serie = '" . $conn->real_escape_string(v('chasis_serie')) . "',
            largo = '" . $conn->real_escape_string(v('largo')) . "',
            ancho = '" . $conn->real_escape_string(v('ancho')) . "',
            alto = '" . $conn->real_escape_string(v('alto')) . "',
            pbv = '" . $conn->real_escape_string(v('pbv')) . "',
            carga_util = '" . $conn->real_escape_string(v('carga_util')) . "',
            ejes = '" . $conn->real_escape_string(v('ejes')) . "'
        WHERE vehiculo_id = $id
    ";

} else {

    // ============================
    // INSERT
    // ============================
    $sql = "
        INSERT INTO vehiculo_ficha_tecnica (
            vehiculo_id, motor_marca, motor_modelo, motor_serie,
            cilindrada, potencia_hp, chasis_marca, chasis_modelo,
            chasis_serie, largo, ancho, alto, pbv, carga_util, ejes
        ) VALUES (
            $id,
            '" . $conn->real_escape_string(v('motor_marca')) . "',
            '" . $conn->real_escape_string(v('motor_modelo')) . "',
            '" . $conn->real_escape_string(v('motor_serie')) . "',
            '" . $conn->real_escape_string(v('cilindrada')) . "',
            '" . $conn->real_escape_string(v('potencia_hp')) . "',
            '" . $conn->real_escape_string(v('chasis_marca')) . "',
            '" . $conn->real_escape_string(v('chasis_modelo')) . "',
            '" . $conn->real_escape_string(v('chasis_serie')) . "',
            '" . $conn->real_escape_string(v('largo')) . "',
            '" . $conn->real_escape_string(v('ancho')) . "',
            '" . $conn->real_escape_string(v('alto')) . "',
            '" . $conn->real_escape_string(v('pbv')) . "',
            '" . $conn->real_escape_string(v('carga_util')) . "',
            '" . $conn->real_escape_string(v('ejes')) . "'
        )
    ";
}

if ($conn->query($sql)) {
    echo json_encode(array("success" => true));
} else {
    echo json_encode(array("success" => false, "msg" => $conn->error));
}
