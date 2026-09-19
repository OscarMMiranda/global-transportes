<?php
//  archivo: modulos/orden_trabajo/controllers/CrearOTController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

/* ============================================================
   MENSAJES CORPORATIVOS
   ============================================================ */
$mensajes = array(
    "numero_ot"  => "El número de OT es obligatorio.",
    "fecha"      => "Debe seleccionar una fecha.",
    "semana_ot"  => "La semana de la OT es obligatoria.",
    "cliente_id" => "Debe seleccionar un cliente para continuar.",
    "empresa_id" => "Debe seleccionar una empresa.",
    "tipo_ot_id" => "Debe seleccionar el tipo de orden.",
    "estado_id"  => "Debe seleccionar un estado."
);

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
            "msg" => $mensajes[$campo]
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
    estado_id,
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
        "msg" => "Orden creada correctamente."
    ]);
} else {
    echo json_encode([
        "ok" => false,
        "msg" => "Error al crear la Orden de Trabajo."
    ]);
}
