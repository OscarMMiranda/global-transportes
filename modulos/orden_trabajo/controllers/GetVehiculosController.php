<?php
	//	archivo: modulos/orden_trabajo/controllers/GetVehiculosController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

$fecha_viaje = isset($_POST['fecha_viaje']) ? $_POST['fecha_viaje'] : null;

if (!$fecha_viaje) {
    echo json_encode(["ok" => false, "msg" => "Fecha de viaje no recibida"]);
    exit;
}

/*
   TRACTOS REALES SEGÚN TU BD:
   tipo_id IN (7, 8)
*/

$sql = "
SELECT 
    v.id,
    v.placa
FROM vehiculos v
INNER JOIN asignaciones_conductor ac
    ON ac.vehiculo_tracto_id = v.id
    AND ac.estado_id = 1
    AND ac.tipo_asignacion = 'tracto'
    AND ac.fecha_inicio <= '$fecha_viaje'
    AND (ac.fecha_fin IS NULL OR ac.fecha_fin >= '$fecha_viaje')
WHERE v.activo = 1
AND v.fecha_borrado IS NULL
AND v.tipo_id IN (7, 8)
ORDER BY v.placa ASC
";

$res = $conn->query($sql);

if (!$res) {
    echo json_encode(["ok" => false, "msg" => $conn->error]);
    exit;
}

$data = [];

while ($row = $res->fetch_assoc()) {
    $data[] = [
        "id"    => intval($row["id"]),
        "placa" => $row["placa"]
    ];
}

echo json_encode([
    "ok"   => true,
    "data" => $data
]);
exit;
