<?php
// ARCHIVO: /modulos/orden_trabajo/controllers/ActualizarController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

// ===============================
// VALIDAR ID
// ===============================
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode(["ok" => false, "msg" => "ID inválido."]);
    exit;
}

// ===============================
// VALIDAR CAMPOS OBLIGATORIOS
// ===============================
$required = array(
    "numero_ot",
    "fecha",
    "cliente_id",
    "empresa_id",
    "tipo_ot_id"
);

foreach ($required as $r) {
    if (!isset($_POST[$r]) || trim($_POST[$r]) === "") {
        echo json_encode(["ok" => false, "msg" => "Campo obligatorio: $r"]);
        exit;
    }
}

// ===============================
// SANITIZAR DATOS
// ===============================
$numero_ot   = trim($_POST["numero_ot"]);
$fecha       = trim($_POST["fecha"]);
$cliente_id  = intval($_POST["cliente_id"]);
$empresa_id  = intval($_POST["empresa_id"]);
$tipo_ot_id  = intval($_POST["tipo_ot_id"]);

$oc_cliente     = isset($_POST["oc_cliente"]) ? trim($_POST["oc_cliente"]) : null;
$numero_dam     = isset($_POST["numero_dam"]) ? trim($_POST["numero_dam"]) : null;
$numero_booking = isset($_POST["numero_booking"]) ? trim($_POST["numero_booking"]) : null;
$otros          = isset($_POST["otros"]) ? trim($_POST["otros"]) : null;

// ===============================
// CALCULAR SEMANA ISO
// ===============================
$semana_ot = intval(date('W', strtotime($fecha)));
if ($semana_ot <= 0) { $semana_ot = 1; }

// ===============================
// LIMPIAR CAMPOS SEGÚN TIPO DE OT
// ===============================
$sqlTipo = "SELECT nombre FROM tipo_ot WHERE id = $tipo_ot_id LIMIT 1";
$resTipo = $conn->query($sqlTipo);
$tipoNombre = "";

if ($resTipo && $resTipo->num_rows > 0) {
    $tmp = $resTipo->fetch_assoc();
    $tipoNombre = strtoupper($tmp["nombre"]);
}

if ($tipoNombre === "IMPORTACION" || $tipoNombre === "IMPORTACIÓN") {
    $numero_booking = null;
    $otros = null;
}

if ($tipoNombre === "EXPORTACION" || $tipoNombre === "EXPORTACIÓN") {
    $numero_dam = null;
    $otros = null;
}

if ($tipoNombre === "NACIONAL") {
    $numero_dam = null;
    $numero_booking = null;
}

// ===============================
// SQL SEGURO (PREPARED STATEMENT)
// ===============================
$sql = "
UPDATE ordenes_trabajo SET
    numero_ot       = ?,
    fecha           = ?,
    semana_ot       = ?,
    cliente_id      = ?,
    empresa_id      = ?,
    tipo_ot_id      = ?,
    oc_cliente      = ?,
    numero_dam      = ?,
    numero_booking  = ?,
    otros           = ?,
    modificado_por  = ?,
    ip_origen       = ?
WHERE id = ?
";

$stmt = $conn->prepare($sql);

$modificado_por = isset($_SESSION["usuario_id"]) ? intval($_SESSION["usuario_id"]) : null;
$ip_origen      = $_SERVER["REMOTE_ADDR"];

$stmt->bind_param(
    "ssiissssssisi",
    $numero_ot,
    $fecha,
    $semana_ot,
    $cliente_id,
    $empresa_id,
    $tipo_ot_id,
    $oc_cliente,
    $numero_dam,
    $numero_booking,
    $otros,
    $modificado_por,
    $ip_origen,
    $id
);

$res = $stmt->execute();

// ===============================
// RESPUESTA
// ===============================
echo json_encode([
    "ok" => $res ? true : false,
    "msg" => $res ? "Orden actualizada correctamente." : "Error al actualizar: " . $stmt->error
]);

$stmt->close();
$conn->close();
exit;
