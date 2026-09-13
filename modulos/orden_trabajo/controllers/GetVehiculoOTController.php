<?php
	//	archivo: modulos/orden_trabajo/controllers/GetVehiculoOTController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

if (!isset($_POST['orden_trabajo_id'])) {
    echo json_encode(["ok" => false, "msg" => "OT no recibida"]);
    exit;
}

$ot_id = intval($_POST['orden_trabajo_id']);

/* 1. OT */
$sql_ot = "
SELECT fecha, semana_ot
FROM ordenes_trabajo
WHERE id = $ot_id
LIMIT 1
";
$res_ot = $conn->query($sql_ot);

if (!$res_ot || $res_ot->num_rows === 0) {
    echo json_encode(["ok" => false, "msg" => "OT no encontrada"]);
    exit;
}

$ot = $res_ot->fetch_assoc();
$fecha_ot  = $ot['fecha'];
$semana_ot = $ot['semana_ot'];

/* 2. ORDEN DE VEHÍCULO */
$sql_ov = "
SELECT id
FROM ordenes_vehiculo
WHERE orden_trabajo_id = $ot_id
LIMIT 1
";
$res_ov = $conn->query($sql_ov);

$orden_vehiculo_id = null;

if ($res_ov && $res_ov->num_rows > 0) {
    $ov = $res_ov->fetch_assoc();
    $orden_vehiculo_id = $ov['id'];
}

/* 3. VIAJES REALES */
$cantidad_viajes = 0;

if ($orden_vehiculo_id) {

    $sql_vo = "
    SELECT id
    FROM viajes_orden
    WHERE orden_vehiculo_id = $orden_vehiculo_id
    ";
    $res_vo = $conn->query($sql_vo);

    if ($res_vo) {
        $cantidad_viajes = $res_vo->num_rows;
    }
}

/* RESPUESTA */
echo json_encode([
    "ok" => true,
    "fecha_ot"        => $fecha_ot,
    "semana_ot"       => $semana_ot,
    "orden_vehiculo_id" => $orden_vehiculo_id,
    "cantidad_viajes" => $cantidad_viajes
]);
exit;
