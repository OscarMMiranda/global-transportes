<?php
// archivo: /modulos/orden_trabajo/controllers/CerrarViajesController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

/* ============================================================
   VALIDAR INPUT
   ============================================================ */
$ot_id = isset($_POST['orden_trabajo_id']) ? intval($_POST['orden_trabajo_id']) : 0;

if ($ot_id <= 0) {
    echo json_encode([
        "ok" => false,
        "msg" => "ID de Orden de Trabajo inválido."
    ]);
    exit;
}

/* ============================================================
   VALIDAR QUE EXISTAN VIAJES
   ============================================================ */
$sql_viajes = "
SELECT id, estado_viaje
FROM ot_viajes
WHERE orden_trabajo_id = $ot_id
";

$res_viajes = $conn->query($sql_viajes);

if (!$res_viajes || $res_viajes->num_rows === 0) {
    echo json_encode([
        "ok" => false,
        "msg" => "La OT no tiene viajes registrados."
    ]);
    exit;
}

/* ============================================================
   VALIDAR QUE TODOS LOS VIAJES ESTÉN CERRADOS
   ============================================================ */
$pendientes = 0;

while ($v = $res_viajes->fetch_assoc()) {
    if (strtolower($v["estado_viaje"]) !== "cerrado") {
        $pendientes++;
    }
}

if ($pendientes > 0) {
    echo json_encode([
        "ok" => false,
        "msg" => "Aún existen viajes pendientes. No se puede cerrar la OT."
    ]);
    exit;
}

/* ============================================================
   ACTUALIZAR ESTADO DE LA OT → COMPLETADA (3)
   ============================================================ */
$sql_estado = "
UPDATE ordenes_trabajo
SET estado_id = 3
WHERE id = $ot_id
";

$conn->query($sql_estado);

if ($conn->error) {
    echo json_encode([
        "ok" => false,
        "msg" => "Error al actualizar estado de la OT: " . $conn->error
    ]);
    exit;
}

/* ============================================================
   RESPUESTA
   ============================================================ */
echo json_encode([
    "ok" => true,
    "msg" => "Todos los viajes cerrados. La OT ha sido marcada como COMPLETADA."
]);

exit;
