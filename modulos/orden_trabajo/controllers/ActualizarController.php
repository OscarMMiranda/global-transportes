<?php
// ======================================================
//  CONTROLADOR: ActualizarController.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Actualizar OT existente
//  GLOBAL 2026 — Arquitectura Limpia
// ======================================================

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
// CALCULAR SEMANA ISO CORPORATIVA
// ===============================
$weekNumber = date('W', strtotime($fecha));     // 02
$yearNumber = date('o', strtotime($fecha));     // 2026 (ISO year)

$semana_ot = 'S' . str_pad($weekNumber, 2, '0', STR_PAD_LEFT) . '-' . $yearNumber;



// ===============================
// OBTENER NOMBRE DEL TIPO DE OT
// ===============================
$sqlTipo = "SELECT nombre FROM tipo_ot WHERE id = $tipo_ot_id LIMIT 1";
$resTipo = $conn->query($sqlTipo);

if (!$resTipo || $resTipo->num_rows === 0) {
    echo json_encode(["ok" => false, "msg" => "Tipo de OT inválido."]);
    exit;
}

$tmp = $resTipo->fetch_assoc();
$tipoNombre = strtoupper($tmp["nombre"]);

// ===============================
// LIMPIAR CAMPOS SEGÚN TIPO DE OT
// ===============================
switch ($tipoNombre) {
    case "IMPORTACION":
    case "IMPORTACIÓN":
        $numero_booking = null;
        $otros = null;
        break;

    case "EXPORTACION":
    case "EXPORTACIÓN":
        $numero_dam = null;
        $otros = null;
        break;

    case "NACIONAL":
        $numero_dam = null;
        $numero_booking = null;
        break;
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
