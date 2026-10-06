<?php
// ======================================================
// API: ot_viaje_form_api.php
// MÓDULO: Ordenes de Trabajo
// RESPONSABILIDAD: Obtener datos para registrar viaje
// PHP 5.6
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
// VALIDAR
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
// OT
// ======================================================

$sql = "

SELECT

    ot.id,
    ot.numero_ot,

    ot.oc_cliente,

    ot.numero_dam,
    ot.numero_booking,
    ot.otros,

    c.nombre AS cliente,

    t.nombre AS tipo_ot,

    e.razon_social AS empresa

FROM ordenes_trabajo ot

LEFT JOIN clientes c
    ON c.id = ot.cliente_id

LEFT JOIN tipo_ot t
    ON t.id = ot.tipo_ot_id

LEFT JOIN empresa e
    ON e.id = ot.empresa_id

WHERE ot.id = ?

LIMIT 1

";

$stmt = $conn->prepare($sql);

if (!$stmt) {

    echo json_encode(array(
        'ok'  => false,
        'msg' => $conn->error
    ));

}