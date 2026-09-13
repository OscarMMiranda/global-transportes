<?php
// archivo: modulos/orden_trabajo/controllers/CrearViajeController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

/* ============================================================
   VALIDAR INPUT
   ============================================================ */
$ot_id         = isset($_POST['orden_trabajo_id']) ? intval($_POST['orden_trabajo_id']) : 0;
$fecha_viaje   = isset($_POST['fecha_viaje']) ? $_POST['fecha_viaje'] : null;
$semana_viaje  = isset($_POST['semana_viaje']) ? $_POST['semana_viaje'] : null;
$origen        = isset($_POST['origen']) ? trim($_POST['origen']) : "";
$destino       = isset($_POST['destino']) ? trim($_POST['destino']) : "";
$observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : "";

if ($ot_id <= 0 || !$fecha_viaje || !$semana_viaje) {
    echo json_encode(["ok" => false, "msg" => "Datos incompletos para registrar viaje"]);
    exit;
}

/* ============================================================
   1. CALCULAR NÚMERO DE VIAJE (por OT)
   ============================================================ */
$sql_count = "
SELECT COUNT(*) AS total
FROM ot_viajes
WHERE orden_trabajo_id = $ot_id
";

$res_count = $conn->query($sql_count);
$row_count = $res_count->fetch_assoc();
$numero_viaje = intval($row_count['total']) + 1;

/* ============================================================
   2. INSERTAR VIAJE EN ot_viajes
   ============================================================ */
$sql_insert = "
INSERT INTO ot_viajes (
    orden_trabajo_id,
    numero_viaje,
    fecha_viaje,
    semana_viaje,
    origen,
    destino,
    observaciones,
    estado_viaje,
    created_at
) VALUES (
    $ot_id,
    $numero_viaje,
    '$fecha_viaje',
    '$semana_viaje',
    '$origen',
    '$destino',
    '$observaciones',
    'pendiente',
    NOW()
)
";

if (!$conn->query($sql_insert)) {
    echo json_encode(["ok" => false, "msg" => "Error al crear viaje"]);
    exit;
}

$viaje_id = $conn->insert_id;

/* ============================================================
   3. RESPUESTA
   ============================================================ */
echo json_encode([
    "ok" => true,
    "viaje_id" => $viaje_id,
    "numero_viaje" => $numero_viaje,
    "fecha_viaje" => $fecha_viaje,
    "semana_viaje" => $semana_viaje
]);
exit;
