<?php
// ======================================================
//  CONTROLLER: GetVehiculoOTController.php
//  RESPONSABILIDAD: Obtener vehículo + viajes asociados a una OT
//  GLOBAL 2026 — Arquitectura Limpia (Versión 4.8)
// ======================================================

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

// ---------------------------------------------
// Validar parámetro
// ---------------------------------------------
if (!isset($_POST['orden_trabajo_id'])) {
    echo json_encode([
        "ok" => false,
        "msg" => "OT no recibida"
    ]);
    exit;
}

$ot_id = intval($_POST['orden_trabajo_id']);

if ($ot_id <= 0) {
    echo json_encode([
        "ok" => false,
        "msg" => "ID de OT no válido"
    ]);
    exit;
}

// ---------------------------------------------
// 1. Obtener datos de la OT
// ---------------------------------------------
$sql_ot = "
    SELECT fecha, semana_ot
    FROM ordenes_trabajo
    WHERE id = ?
    LIMIT 1
";

$stmt_ot = $conn->prepare($sql_ot);

if (!$stmt_ot) {
    echo json_encode([
        "ok" => false,
        "msg" => "Error al preparar consulta de OT"
    ]);
    exit;
}

$stmt_ot->bind_param("i", $ot_id);
$stmt_ot->execute();
$res_ot = $stmt_ot->get_result();

if (!$res_ot || $res_ot->num_rows === 0) {
    echo json_encode([
        "ok" => false,
        "msg" => "OT no encontrada"
    ]);
    exit;
}

$ot = $res_ot->fetch_assoc();
$fecha_ot  = $ot['fecha'];
$semana_ot = $ot['semana_ot'];

// ---------------------------------------------
// 2. Obtener orden de vehículo asociada
// ---------------------------------------------
$sql_ov = "
    SELECT id
    FROM ordenes_vehiculo
    WHERE orden_trabajo_id = ?
      AND deleted_at IS NULL
    LIMIT 1
";

$stmt_ov = $conn->prepare($sql_ov);

if (!$stmt_ov) {
    echo json_encode([
        "ok" => false,
        "msg" => "Error al preparar consulta de orden de vehículo"
    ]);
    exit;
}

$stmt_ov->bind_param("i", $ot_id);
$stmt_ov->execute();
$res_ov = $stmt_ov->get_result();

$orden_vehiculo_id = null;

if ($res_ov && $res_ov->num_rows > 0) {
    $ov = $res_ov->fetch_assoc();
    $orden_vehiculo_id = $ov['id'];
}

// ---------------------------------------------
// 3. Contar viajes reales
// ---------------------------------------------
$cantidad_viajes = 0;

if ($orden_vehiculo_id) {

    $sql_vo = "
        SELECT id
        FROM viajes_orden
        WHERE orden_vehiculo_id = ?
    ";

    $stmt_vo = $conn->prepare($sql_vo);

    if ($stmt_vo) {
        $stmt_vo->bind_param("i", $orden_vehiculo_id);
        $stmt_vo->execute();
        $res_vo = $stmt_vo->get_result();

        if ($res_vo) {
            $cantidad_viajes = $res_vo->num_rows;
        }
    }
}

// ---------------------------------------------
// Respuesta final
// ---------------------------------------------
echo json_encode([
    "ok" => true,
    "fecha_ot"          => $fecha_ot,
    "semana_ot"         => $semana_ot,
    "orden_vehiculo_id" => $orden_vehiculo_id,
    "cantidad_viajes"   => $cantidad_viajes
]);
exit;
