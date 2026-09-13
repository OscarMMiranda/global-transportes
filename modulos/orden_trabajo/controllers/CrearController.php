<?php
// archivo: /modulos/orden_trabajo/controllers/CrearController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

/* ============================================================
   VALIDAR CAMPOS OBLIGATORIOS
   ============================================================ */
$campos_obligatorios = array(
    "numero_ot", "fecha", "semana_ot",
    "cliente_id", "empresa_id", "tipo_ot_id"
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
$numero_ot      = trim($_POST["numero_ot"]);
$fecha          = trim($_POST["fecha"]);
$semana_ot      = intval($_POST["semana_ot"]);
$cliente_id     = intval($_POST["cliente_id"]);
$empresa_id     = intval($_POST["empresa_id"]);
$tipo_ot_id     = intval($_POST["tipo_ot_id"]);

$oc_cliente     = isset($_POST["oc_cliente"]) ? trim($_POST["oc_cliente"]) : null;
$numero_dam     = isset($_POST["numero_dam"]) ? trim($_POST["numero_dam"]) : null;
$numero_booking = isset($_POST["numero_booking"]) ? trim($_POST["numero_booking"]) : null;
$otros          = isset($_POST["otros"]) ? trim($_POST["otros"]) : null;

/* ============================================================
   ESTADO AUTOMÁTICO
   ============================================================ */
// 1 = Pendiente
$estado_id = 1;

/* ============================================================
   INSERTAR EN BD (PREPARED STATEMENT)
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
    otros,
    creado_por,
    ip_origen
)
VALUES
(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
";

$stmt = $conn->prepare($sql);

$creado_por = isset($_SESSION["usuario_id"]) ? intval($_SESSION["usuario_id"]) : null;
$ip_origen  = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : null;

$stmt->bind_param(
    "ssiissssssiss",
    $numero_ot,
    $fecha,
    $semana_ot,
    $cliente_id,
    $empresa_id,
    $tipo_ot_id,
    $estado_id,
    $oc_cliente,
    $numero_dam,
    $numero_booking,
    $otros,
    $creado_por,
    $ip_origen
);

$ok = $stmt->execute();

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
        "msg" => "Error al crear la Orden de Trabajo: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
