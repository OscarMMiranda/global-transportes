<?php
header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

if (!$conn) {
    echo json_encode(["ok" => false, "msg" => "Error de conexión"]);
    exit;
}

$id = isset($_POST['vehiculo_id']) ? intval($_POST['vehiculo_id']) : 0;

if ($id <= 0) {
    echo json_encode(["ok" => false, "msg" => "ID inválido"]);
    exit;
}

function f($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : "";
}

$sql = "SELECT id FROM vehiculo_ficha_tecnica WHERE vehiculo_id = $id LIMIT 1";
$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {

    $sqlUpdate = "
        UPDATE vehiculo_ficha_tecnica SET
            motor_marca   = '" . $conn->real_escape_string(f('motor_marca')) . "',
            motor_modelo  = '" . $conn->real_escape_string(f('motor_modelo')) . "',
            motor_serie   = '" . $conn->real_escape_string(f('motor_serie')) . "',
            cilindrada    = '" . $conn->real_escape_string(f('cilindrada')) . "',
            potencia_hp   = '" . $conn->real_escape_string(f('potencia_hp')) . "',

            chasis_marca  = '" . $conn->real_escape_string(f('chasis_marca')) . "',
            chasis_modelo = '" . $conn->real_escape_string(f('chasis_modelo')) . "',
            chasis_serie  = '" . $conn->real_escape_string(f('chasis_serie')) . "',

            largo         = '" . $conn->real_escape_string(f('largo')) . "',
            ancho         = '" . $conn->real_escape_string(f('ancho')) . "',
            alto          = '" . $conn->real_escape_string(f('alto')) . "',

            pbv           = '" . $conn->real_escape_string(f('pbv')) . "',
            carga_util    = '" . $conn->real_escape_string(f('carga_util')) . "',
            ejes          = '" . $conn->real_escape_string(f('ejes')) . "'

        WHERE vehiculo_id = $id
        LIMIT 1
    ";

    if ($conn->query($sqlUpdate)) {
        echo json_encode(["ok" => true, "msg" => "Ficha técnica actualizada"]);
    } else {
        echo json_encode(["ok" => false, "msg" => "Error al actualizar: " . $conn->error]);
    }

} else {

    $sqlInsert = "
        INSERT INTO vehiculo_ficha_tecnica (
            vehiculo_id,
            motor_marca, motor_modelo, motor_serie, cilindrada, potencia_hp,
            chasis_marca, chasis_modelo, chasis_serie,
            largo, ancho, alto,
            pbv, carga_util, ejes
        ) VALUES (
            $id,
            '" . $conn->real_escape_string(f('motor_marca')) . "',
            '" . $conn->real_escape_string(f('motor_modelo')) . "',
            '" . $conn->real_escape_string(f('motor_serie')) . "',
            '" . $conn->real_escape_string(f('cilindrada')) . "',
            '" . $conn->real_escape_string(f('potencia_hp')) . "',

            '" . $conn->real_escape_string(f('chasis_marca')) . "',
            '" . $conn->real_escape_string(f('chasis_modelo')) . "',
            '" . $conn->real_escape_string(f('chasis_serie')) . "',

            '" . $conn->real_escape_string(f('largo')) . "',
            '" . $conn->real_escape_string(f('ancho')) . "',
            '" . $conn->real_escape_string(f('alto')) . "',

            '" . $conn->real_escape_string(f('pbv')) . "',
            '" . $conn->real_escape_string(f('carga_util')) . "',
            '" . $conn->real_escape_string(f('ejes')) . "'
        )
    ";

    if ($conn->query($sqlInsert)) {
        echo json_encode(["ok" => true, "msg" => "Ficha técnica creada"]);
    } else {
        echo json_encode(["ok" => false, "msg" => "Error al crear: " . $conn->error]);
    }
}
?>

