<?php
session_start();

// 🔧 Trazabilidad y errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/create_error.log');

// 🔧 Conexión
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';
$conn = getConnection();
if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("❌ Conexión no inicializada");
    die("Error interno: conexión no disponible.");
}

// 🔒 Validación de método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log("❌ Método no permitido: " . $_SERVER['REQUEST_METHOD']);
    header("Location: ../views/create.php?error=Método no permitido.");
    exit();
}

// 🧼 Captura y sanitización
$numeroCorrelativo = trim($_POST['numero_correlativo'] ?? '');
$anioOT            = trim($_POST['anio_ot'] ?? '');
$fecha             = trim($_POST['fecha'] ?? '');
$clienteID         = intval($_POST['cliente_id'] ?? 0);
$tipoOTID          = intval($_POST['tipo_ot_id'] ?? 0);
$empresaID         = intval($_POST['empresa_id'] ?? 0);
$ocCliente         = trim($_POST['oc_cliente'] ?? '');

if (!$numeroCorrelativo || !$anioOT || !$fecha || !$ocCliente || $clienteID <= 0 || $tipoOTID <= 0 || $empresaID <= 0) {
    error_log("⚠️ Faltan datos obligatorios");
    header("Location: ../views/create.php?error=Faltan datos obligatorios.");
    exit();
}

// 🧠 Formateo y cálculo
$numeroOT   = $numeroCorrelativo . '-' . $anioOT;
$semanaOT   = (int) date('W', strtotime($fecha));
$estadoOT   = 1;
$created_at = date('Y-m-d H:i:s');
$creado_por = $_SESSION['usuario_id'] ?? 1;
$ip_origen  = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
$estado_id  = 1;

// 🧩 Campos dinámicos
$numero_dam     = ($tipoOTID === 2) ? trim($_POST['numero_dam'] ?? '') : '';
$numero_booking = ($tipoOTID === 3) ? trim($_POST['numero_booking'] ?? '') : '';
$otros          = ($tipoOTID === 1) ? trim($_POST['otros'] ?? '') : '';

// 🧩 Campos adicionales con valores por defecto válidos
$zona_id             = 1;
$tipo_mercaderia_id  = 0;
$origen_id           = 0;
$destino_id          = 0;
$modificado_por      = null;

// 🔎 Verificación de duplicado
$check_stmt = $conn->prepare("SELECT id FROM ordenes_trabajo WHERE numero_ot = ?");
$check_stmt->bind_param("s", $numeroOT);
$check_stmt->execute();
$check_stmt->store_result();
if ($check_stmt->num_rows > 0) {
    error_log("⚠️ Duplicado detectado: $numeroOT");
    header("Location: ../views/create.php?error=El número de OT ya existe.");
    exit();
}

// 💾 Inserción segura
$sql = "INSERT INTO ordenes_trabajo (
    numero_ot, fecha, semana_ot, estado_ot, cliente_id, tipo_ot_id, empresa_id, oc_cliente,
    numero_dam, numero_booking, otros,
    zona_id, tipo_mercaderia_id, origen_id, destino_id,
    created_at, creado_por, modificado_por, ip_origen, estado_id
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("❌ Error en prepare(): " . $conn->error);
    die("❌ Error en prepare(): " . $conn->error);
}

$stmt->bind_param(
    "ssiiiiissssiiiissss",
    $numeroOT, $fecha, $semanaOT, $estadoOT, $clienteID, $tipoOTID, $empresaID, $ocCliente,
    $numero_dam, $numero_booking, $otros,
    $zona_id, $tipo_mercaderia_id, $origen_id, $destino_id,
    $created_at, $creado_por, $modificado_por, $ip_origen, $estado_id
);

if ($stmt->execute()) {
    error_log("✅ OT creada correctamente: $numeroOT");
    header("Location: ../views/list.php?success=✅ Orden creada correctamente.");
    exit();
} else {
    error_log("❌ Error en execute(): " . $stmt->error);
    die("❌ Error al guardar la orden: " . $stmt->error);
}