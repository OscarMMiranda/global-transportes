<?php
// ============================================================
// CONTROLADOR: GetVehiculosController.php
// RESPONSABILIDAD: Obtener tractos disponibles para una fecha
// ARQUITECTURA LIMPIA — PHP 5.6 COMPATIBLE
// ============================================================

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

/* ============================================================
   VALIDAR INPUT
   ============================================================ */
$fecha_viaje = isset($_POST['fecha_viaje']) ? trim($_POST['fecha_viaje']) : "";

if ($fecha_viaje === "") {
    echo json_encode(["ok" => false, "msg" => "Fecha de viaje no recibida"]);
    exit;
}

/* ============================================================
   SQL: TRACTOS ACTIVOS + ASIGNACIÓN VÁLIDA EN LA FECHA
   ============================================================ */
$sql = "
SELECT 
    v.id,
    v.placa
FROM vehiculos v
INNER JOIN asignaciones_conductor ac
    ON ac.vehiculo_tracto_id = v.id
    AND ac.estado_id = 1
    AND ac.tipo_asignacion = 'tracto'
    AND ac.fecha_inicio <= '$fecha_viaje'
    AND (ac.fecha_fin IS NULL OR ac.fecha_fin >= '$fecha_viaje')
WHERE v.activo = 1
AND v.fecha_borrado IS NULL
AND v.tipo_id IN (7, 8)
ORDER BY v.placa ASC
";

/* ============================================================
   EJECUTAR CONSULTA
   ============================================================ */
$res = $conn->query($sql);

if (!$res) {
    echo json_encode([
        "ok"  => false,
        "msg" => "Error SQL: " . $conn->error
    ]);
    exit;
}

/* ============================================================
   ARMAR RESPUESTA
   ============================================================ */
$data = array();

while ($row = $res->fetch_assoc()) {
    $data[] = array(
        "id"    => intval($row["id"]),
        "placa" => $row["placa"]
    );
}

/* ============================================================
   RESPUESTA FINAL
   ============================================================ */
echo json_encode([
    "ok"   => true,
    "data" => $data
]);
exit;
