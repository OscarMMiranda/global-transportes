<?php
// ======================================================
//  CONTROLLER: GetOrdenVehiculoController.php
//  RESPONSABILIDAD: Obtener vehículo asociado a una OT
//  GLOBAL 2026 — Arquitectura Limpia (Versión 4.7)
// ======================================================

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

// Validar parámetro
if (!isset($_POST['orden_trabajo_id'])) {
    echo json_encode([
        "ok" => false,
        "msg" => "Parámetro orden_trabajo_id no enviado"
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

// Consulta segura
$sql = "
    SELECT id, fecha_salida, semana_viaje
    FROM ordenes_vehiculo
    WHERE orden_trabajo_id = ?
      AND deleted_at IS NULL
    LIMIT 1
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "ok" => false,
        "msg" => "Error al preparar consulta"
    ]);
    exit;
}

$stmt->bind_param("i", $ot_id);
$stmt->execute();

$res = $stmt->get_result();

if (!$res || $res->num_rows === 0) {
    echo json_encode([
        "ok" => false,
        "msg" => "No existe vehículo asociado a la OT."
    ]);
    exit;
}

$row = $res->fetch_assoc();

echo json_encode([
    "ok" => true,
    "orden_vehiculo_id" => $row['id'],
    "fecha_salida"      => $row['fecha_salida'],
    "semana_viaje"      => $row['semana_viaje']
]);
exit;
