<?php
// ============================================================
// CONTROLADOR: CrearViajeBasicoController.php
// RESPONSABILIDAD: Registrar un viaje básico (solo fecha + semana)
// ARQUITECTURA LIMPIA — PHP 5.6 COMPATIBLE
// ============================================================

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

/* ============================================================
   VALIDAR INPUT
   ============================================================ */
$ot_id  = isset($_POST['orden_trabajo_id']) ? intval($_POST['orden_trabajo_id']) : 0;
$fecha  = isset($_POST['fecha_viaje']) ? trim($_POST['fecha_viaje']) : "";
$semana = isset($_POST['semana_viaje']) ? trim($_POST['semana_viaje']) : "";

if ($ot_id <= 0) {
    echo json_encode(["ok" => false, "msg" => "OT inválida"]);
    exit;
}

if ($fecha === "") {
    echo json_encode(["ok" => false, "msg" => "Fecha de viaje no recibida"]);
    exit;
}

if ($semana === "") {
    echo json_encode(["ok" => false, "msg" => "Semana de viaje no recibida"]);
    exit;
}

/* ============================================================
   INSERTAR VIAJE BÁSICO
   ============================================================ */
$sql = "
INSERT INTO ot_viajes (
    orden_trabajo_id,
    fecha_viaje,
    semana_viaje,
    created_at
)
VALUES (
    $ot_id,
    '$fecha',
    '$semana',
    NOW()
)
";

if (!$conn->query($sql)) {
    echo json_encode([
        "ok"  => false,
        "msg" => "Error al guardar: " . $conn->error
    ]);
    exit;
}

/* ============================================================
   RESPUESTA FINAL
   ============================================================ */
echo json_encode([
    "ok" => true,
    "msg" => "Viaje registrado correctamente"
]);
exit;
