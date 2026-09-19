<?php
// archivo: modulos/orden_trabajo/acciones/obtener_logistica.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header("Content-Type: application/json");

/* ============================================================
   VALIDAR INPUT
   ============================================================ */
if (!isset($_POST["ot_id"])) {
    echo json_encode([
        "ok" => false,
        "msg" => "No se recibió el ID de la OT."
    ]);
    exit;
}

$ot_id = intval($_POST["ot_id"]);

/* ============================================================
   1. DATOS DE LA OT
   ============================================================ */
$sql_ot = "
SELECT 
    ot.id,
    ot.numero_ot,
    ot.fecha,
    ot.semana_ot,
    COALESCE(c.nombre_comercial, c.nombre) AS cliente,
    COALESCE(e.nombre_comercial, e.razon_social) AS empresa
FROM ordenes_trabajo ot
LEFT JOIN clientes c ON ot.cliente_id = c.id
LEFT JOIN empresa e ON ot.empresa_id = e.id
WHERE ot.id = $ot_id
LIMIT 1
";

$res_ot = $conn->query($sql_ot);

if (!$res_ot || $res_ot->num_rows === 0) {
    echo json_encode([
        "ok" => false,
        "msg" => "No se encontró información de la OT."
    ]);
    exit;
}

$ot = $res_ot->fetch_assoc();

/* ============================================================
   2. ORDEN DE VEHÍCULO (SI EXISTE)
   ============================================================ */
$sql_ov = "
SELECT id, fecha_salida, semana_viaje
FROM ordenes_vehiculo
WHERE orden_trabajo_id = $ot_id
  AND deleted_at IS NULL
LIMIT 1
";

$res_ov = $conn->query($sql_ov);

$orden_vehiculo_id = null;
$fecha_salida = null;
$semana_viaje = null;

if ($res_ov && $res_ov->num_rows > 0) {
    $ov = $res_ov->fetch_assoc();
    $orden_vehiculo_id = $ov["id"];
    $fecha_salida      = $ov["fecha_salida"];
    $semana_viaje      = $ov["semana_viaje"];
}

/* ============================================================
   3. CONTAR VIAJES
   ============================================================ */
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

/* ============================================================
   RESPUESTA CORPORATIVA
   ============================================================ */
echo json_encode([
    "ok" => true,
    "msg" => "Datos obtenidos correctamente.",
    "ot" => $ot,
    "orden_vehiculo_id" => $orden_vehiculo_id,
    "fecha_salida"      => $fecha_salida,
    "semana_viaje"      => $semana_viaje,
    "cantidad_viajes"   => $cantidad_viajes
]);
exit;
