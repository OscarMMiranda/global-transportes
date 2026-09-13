<?php
// archivo: /modulos/orden_trabajo/controllers/CrearController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

/* ============================================================
   VALIDAR CAMPOS
   ============================================================ */
$campos_obligatorios = array(
    "numero_ot", "fecha", "semana_ot",
    "cliente_id", "empresa_id", "tipo_ot_id", "estado_id"
);

foreach ($campos_obligatorios as $campo) {
    if (!isset($_POST[$campo]) || trim($_POST[$campo]) === "") {
        echo json_encode([
            "ok" => false,
            "msg" => "El campo '$campo' es obligatorio."
        ]);
        exit;
    }
}

/* ============================================================
   RECIBIR CAMPOS
   ============================================================ */
$numero_ot      = $conn->real_escape_string($_POST["numero_ot"]);
$fecha          = $conn->real_escape_string($_POST["fecha"]);
$semana_ot      = intval($_POST["semana_ot"]);
$cliente_id     = intval($_POST["cliente_id"]);
$empresa_id     = intval($_POST["empresa_id"]);
$tipo_ot_id     = intval($_POST["tipo_ot_id"]);
$estado_id      = intval($_POST["estado_id"]);

$oc_cliente     = isset($_POST["oc_cliente"]) ? $conn->real_escape_string($_POST["oc_cliente"]) : "";
$numero_dam     = isset($_POST["numero_dam"]) ? $conn->real_escape_string($_POST["numero_dam"]) : "";
$numero_booking = isset($_POST["numero_booking"]) ? $conn->real_escape_string($_POST["numero_booking"]) : "";
$otros          = isset($_POST["otros"]) ? $conn->real_escape_string($_POST["otros"]) : "";

/* ============================================================
   INSERTAR EN BD
   ============================================================ */
$sql = "
INSERT INTO ordenes_trabajo
(
    numero_ot,
    fecha,
    semana_ot,
    cliente_id,
    empresa_id,
    tipo_ot_id,
    estado_ot,
    oc_cliente,
    numero_dam,
    numero_booking,
    otros
)
VALUES
(
    '$numero_ot',
    '$fecha',
    $semana_ot,
    $cliente_id,
    $empresa_id,
    $tipo_ot_id,
    $estado_id,
    '$oc_cliente',
    '$numero_dam',
    '$numero_booking',
    '$otros'
)
";

$ok = $conn->query($sql);

/* ============================================================
   RESPUESTA
   ============================================================ */
if ($ok) {
    echo json_encode([
        "ok" => true,
        "msg" => "Orden creada correctamente"
    ]);
} else {
    echo json_encode([
        "ok" => false,
        "msg" => "Error al crear la Orden de Trabajo"
    ]);
}
