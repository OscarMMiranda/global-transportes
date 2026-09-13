 <?php
//  archivo: /modulos/orden_trabajo/controllers/GetConductorPorVehiculo.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

$vehiculo_id  = isset($_POST['vehiculo_id']) ? intval($_POST['vehiculo_id']) : 0;
$fecha_viaje  = isset($_POST['fecha_viaje']) ? $_POST['fecha_viaje'] : null;

if (!$vehiculo_id || !$fecha_viaje) {
    echo json_encode(["ok" => false, "msg" => "Datos incompletos"]);
    exit;
}

$sql = "
SELECT 
    ac.conductor_id,
    CONCAT(c.nombres, ' ', c.apellidos) AS nombre
FROM asignaciones_conductor ac
INNER JOIN conductores c ON c.id = ac.conductor_id
WHERE ac.vehiculo_tracto_id = $vehiculo_id
AND ac.estado_id = 1
AND ac.tipo_asignacion = 'tracto'
AND ac.fecha_inicio <= '$fecha_viaje'
AND (ac.fecha_fin IS NULL OR ac.fecha_fin >= '$fecha_viaje')
AND c.activo = 1
LIMIT 1
";

$res = $conn->query($sql);

if (!$res || $res->num_rows === 0) {
    echo json_encode(["ok" => false, "msg" => "Sin conductor asignado"]);
    exit;
}

$row = $res->fetch_assoc();

echo json_encode([
    "ok" => true,
    "nombre" => $row["nombre"],
    "conductor_id" => intval($row["conductor_id"])
]);
exit;
