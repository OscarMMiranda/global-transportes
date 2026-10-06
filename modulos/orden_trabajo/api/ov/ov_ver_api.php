<?php
// ======================================================
// API: ov_ver_api.php
// MÓDULO: Ordenes de Vehículo
// RESPONSABILIDAD: Obtener viajes (OV) asociados a una OT
// PHP 5.6 Compatible
// ======================================================

header('Content-Type: application/json');

require_once __DIR__ . '/../../../includes/config.php';

$conn = getConnection();

if (!$conn) {

    echo json_encode(array(
        'ok'  => false,
        'msg' => 'Error de conexión'
    ));

    exit;
}


// ======================================================
// VALIDAR OT
// ======================================================

$id_ot = isset($_POST['id_ot'])
    ? intval($_POST['id_ot'])
    : 0;

if ($id_ot <= 0) {

    echo json_encode(array(
        'ok'  => false,
        'msg' => 'ID OT inválido'
    ));

    exit;
}


// ======================================================
// DATOS GENERALES DE LA OT
// ======================================================

$sqlOT = "

SELECT

    ot.id,
    ot.numero_ot,
    ot.oc_cliente,
    ot.numero_dam,
    ot.numero_booking,
    ot.otros,

    c.nombre AS cliente,

    e.razon_social AS empresa,

    t.nombre AS tipo_ot

FROM ordenes_trabajo ot

LEFT JOIN clientes c
    ON c.id = ot.cliente_id

LEFT JOIN empresa e
    ON e.id = ot.empresa_id

LEFT JOIN tipo_ot t
    ON t.id = ot.tipo_ot_id

WHERE ot.id = ?

LIMIT 1

";

$stmt = $conn->prepare($sqlOT);

if (!$stmt) {

    echo json_encode(array(
        'ok'  => false,
        'msg' => $conn->error
    ));

    exit;
}

$stmt->bind_param(
    "i",
    $id_ot
);

$stmt->execute();

$res = $stmt->get_result();

if (
    !$res ||
    $res->num_rows == 0
) {

    echo json_encode(array(
        'ok'  => false,
        'msg' => 'OT no encontrada'
    ));

    exit;
}

$ot = $res->fetch_assoc();


// ======================================================
// OBTENER TODAS LAS OV DE LA OT
// ======================================================

$ovs = array();

$sqlOV = "

SELECT

    ov.id,

    ov.numero_ov,

    ov.fecha_salida,

    ov.estado,

    v.placa AS vehiculo,

    CONCAT(
        c.apellidos,
        ', ',
        c.nombres
    ) AS conductor,

    COALESCE(ov.origen, '') AS origen,

    COALESCE(ov.destino, '') AS destino,

    COALESCE(ov.tipo_carga, '') AS tipo_carga,

    CASE

        WHEN ov.tipo_carga = 'CONTENEDOR'
        THEN COALESCE(ov.numero_contenedor,'')

        ELSE COALESCE(ov.descripcion_carga,'')

    END AS carga

FROM ordenes_vehiculo ov

LEFT JOIN vehiculos v
    ON v.id = ov.vehiculo_id

LEFT JOIN conductores c
    ON c.id = ov.conductor_id

WHERE ov.orden_trabajo_id = ?

ORDER BY ov.numero_ov ASC

";

$stmt = $conn->prepare($sqlOV);

if (!$stmt) {

    echo json_encode(array(
        'ok'  => false,
        'msg' => $conn->error
    ));

    exit;
}

$stmt->bind_param(
    "i",
    $id_ot
);

$stmt->execute();

$res = $stmt->get_result();

while (
    $res &&
    $row = $res->fetch_assoc()
) {

    $ovs[] = array(

        'id' => $row['id'],

        'numero_ov' => $row['numero_ov'],

        'fecha' => $row['fecha_salida'],

        'vehiculo' => $row['vehiculo'],

        'conductor' => $row['conductor'],

        'origen' => $row['origen'],

        'destino' => $row['destino'],

        'tipo_carga' => $row['tipo_carga'],

        'carga' => $row['carga'],

        'estado' => $row['estado']
    );
}


// ======================================================
// RESPUESTA
// ======================================================

echo json_encode(array(

    'ok' => true,

    'ot' => $ot,

    'cantidad_viajes' => count($ovs),

    'ovs' => $ovs

));

exit;