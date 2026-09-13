// archivo: /modulos/viajes/controllers/GetOrdenVehiculoController.php
//
// Obtiene el vehiculo asociado a la OT

<?php
require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

$ot_id = intval($_POST['orden_trabajo_id']);

$sql = "
SELECT id, fecha_salida, semana_viaje
FROM ordenes_vehiculo
WHERE orden_trabajo_id = $ot_id
  AND deleted_at IS NULL
LIMIT 1
";

$res = $conn->query($sql);

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
