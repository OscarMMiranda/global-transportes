<?php
// archivo: /modulos/orden_trabajo/controllers/CancelarController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

/* ============================================================
   VALIDAR INPUT
   ============================================================ */
$ot_id   = isset($_POST['orden_trabajo_id']) ? intval($_POST['orden_trabajo_id']) : 0;
$motivo  = isset($_POST['motivo']) ? trim($_POST['motivo']) : null;

if ($ot_id <= 0) {
    echo json_encode([
        "ok" => false,
        "msg" => "ID de Orden de Trabajo inválido."
    ]);
    exit;
}

/* ============================================================
   VALIDAR QUE LA OT EXISTA
   ============================================================ */
$sql_ot = "
SELECT id
FROM ordenes_trabajo
WHERE id = $ot_id
LIMIT 1
";

$res_ot = $conn->query($sql_ot);

if (!$res_ot || $res_ot->num_rows === 0) {
    echo json_encode([
        "ok" => false,
        "msg" => "La Orden de Trabajo no existe."
    ]);
    exit;
}

/* ============================================================
   ACTUALIZAR ESTADO → CANCELADA (5)
   ============================================================ */
$sql_update = "
UPDATE ordenes_trabajo
SET 
    estado_id = 5,
    observaciones = " . ($motivo ? "'" . $conn->real_escape_string($motivo) . "'" : "observaciones") . ",
    modificado_por = 1,
    ip_origen = '" . $_SERVER['REMOTE_ADDR'] . "'
WHERE id = $ot_id
";

$conn->query($sql_update);

if ($conn->error) {
    echo json_encode([
        "ok" => false,
        "msg" => "Error al cancelar la OT: " . $conn->error
    ]);
    exit;
}

/* ============================================================
   RESPUESTA
   ============================================================ */
echo json_encode([
    "ok" => true,
    "msg" => "OT cancelada correctamente.",
    "motivo" => $motivo
]);

exit;
