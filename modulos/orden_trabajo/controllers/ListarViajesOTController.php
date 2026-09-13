<?php
// archivo: modulos/orden_trabajo/controllers/ListarViajesOTController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

$ot_id = isset($_POST['orden_trabajo_id']) ? intval($_POST['orden_trabajo_id']) : 0;

$sql = "
SELECT 
    ov.id,
    ov.numero_ov AS numero_viaje,
    ov.fecha_salida,
    ov.semana_viaje,
    ov.estado,
    ov.observaciones,

    vh.placa AS vehiculo,

    c.nombres,
    c.apellidos

FROM ordenes_vehiculo ov
LEFT JOIN vehiculos vh ON vh.id = ov.vehiculo_id
LEFT JOIN conductores c ON c.id = ov.conductor_id

WHERE ov.orden_trabajo_id = $ot_id
ORDER BY ov.id ASC
";

$res = $conn->query($sql);

if (!$res) {
    echo json_encode([
        "ok" => false,
        "error" => $conn->error
    ]);
    exit;
}

$data = [];

while ($row = $res->fetch_assoc()) {

    $fecha = date("d/m/Y", strtotime($row["fecha_salida"]));
    $semana = "S" . str_pad($row["semana_viaje"], 2, "0", STR_PAD_LEFT) . "-" . date("Y", strtotime($row["fecha_salida"]));
    $estado = ucfirst(strtolower($row["estado"]));
    $vehiculo = $row["vehiculo"];

    // ============================
    // Opción 2: Primer nombre + primer apellido
    // ============================
    $primerNombre = explode(" ", trim($row["nombres"]))[0];
    $primerApellido = explode(" ", trim($row["apellidos"]))[0];
    $conductor = $primerNombre . " " . $primerApellido;

    $data[] = [
        "numero_viaje"    => $row["numero_viaje"],
        "fecha"           => $fecha,
        "semana"          => $semana,
        "vehiculo"        => $vehiculo,
        "conductor"       => $conductor,
        "origen"          => "-",
        "destino"         => "-",
        "tipo_mercaderia" => "-",
        "estado"          => $estado
    ];
}

echo json_encode([
    "ok" => true,
    "data" => $data
]);
exit;
