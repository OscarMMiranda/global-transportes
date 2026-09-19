<?php
// archivo: /modulos/orden_trabajo/controllers/GetDatosOTController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

/* ============================================================
   VALIDAR INPUT
   ============================================================ */
if (!isset($_POST['orden_trabajo_id'])) {
    echo json_encode(["ok" => false, "msg" => "OT no recibida"]);
    exit;
}

$ot_id = intval($_POST['orden_trabajo_id']);

/* ============================================================
   1. OBTENER FECHA, SEMANA Y ASIGNACIONES DE LA OT
   ============================================================ */
$sql_ot = "
SELECT 
    fecha,
    semana_ot,
    vehiculo_id,
    conductor_id
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

$fecha_ot        = $ot['fecha'];
$semana_ot       = $ot['semana_ot'];
$vehiculo_id     = intval($ot['vehiculo_id']);
$conductor_id    = intval($ot['conductor_id']);

/* ============================================================
   2. CONTAR VIAJES EXISTENTES EN ot_viajes
   ============================================================ */
$sql_viajes = "
SELECT COUNT(*) AS total
FROM ot_viajes
WHERE orden_trabajo_id = $ot_id
";

$res_viajes = $conn->query($sql_viajes);
$row_viajes = $res_viajes->fetch_assoc();

$cantidad_viajes = intval($row_viajes['total']);

/* ============================================================
   3. OBTENER NOMBRE DEL CONDUCTOR (si existe)
   ============================================================ */
$conductor_nombre = "";

if ($conductor_id > 0) {

    $sql_c = "
    SELECT CONCAT(nombres, ' ', apellidos) AS nombre
    FROM conductores
    WHERE id = $conductor_id AND activo = 1
    LIMIT 1
    ";

    $res_c = $conn->query($sql_c);

    if ($res_c && $res_c->num_rows > 0) {
        $row_c = $res_c->fetch_assoc();
        $conductor_nombre = $row_c["nombre"];
    }
}

/* ============================================================
   RESPUESTA COMPLETA
   ============================================================ */
echo json_encode([
    "ok"                => true,
    "fecha_ot"          => $fecha_ot,
    "semana_ot"         => $semana_ot,
    "cantidad_viajes"   => $cantidad_viajes,
    "vehiculo_id"       => $vehiculo_id,
    "conductor_id"      => $conductor_id,
    "conductor_nombre"  => $conductor_nombre
]);
exit;
