<?php
// 📄 crear.php — creación de entidad con trazabilidad completa (PHP 5.6)

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');
error_log("🚀 Entrando a crear.php");
error_log("📨 POST recibido: " . json_encode($_POST));

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();
if (!($conn instanceof mysqli)) {
    error_log("❌ Error de conexión");
    echo "❌ Error de conexión.";
    return;
}

// 🧹 Validar y limpiar datos
$nombre          = isset($_POST['nombre'])        ? trim($_POST['nombre'])        : '';
$ruc             = isset($_POST['ruc'])           ? trim($_POST['ruc'])           : '';
$direccion       = isset($_POST['direccion'])     ? trim($_POST['direccion'])     : '';
$tipo_id         = isset($_POST['tipo_id'])       ? intval($_POST['tipo_id'])     : 0;
$departamento_id = isset($_POST['departamento_id']) ? intval($_POST['departamento_id']) : 0;
$provincia_id    = isset($_POST['provincia_id'])  ? intval($_POST['provincia_id'])  : 0;
$distrito_id     = isset($_POST['distrito_id'])   ? intval($_POST['distrito_id'])   : 0;
$creado_por      = 'admin'; // ⚠️ Reemplazar por $_SESSION['usuario'] si tenés login

error_log("🧪 nombre=$nombre, ruc=$ruc, direccion=$direccion, tipo_id=$tipo_id, departamento_id=$departamento_id, provincia_id=$provincia_id, distrito_id=$distrito_id, creado_por=$creado_por");

if (!$nombre || !$ruc || !$direccion || $tipo_id <= 0 || $departamento_id <= 0 || $provincia_id <= 0 || $distrito_id <= 0 || !$creado_por) {
    error_log("❌ Datos incompletos");
    echo "❌ Datos incompletos.";
    return;
}

// 🔍 Validar RUC único
$sqlRuc = "SELECT id FROM entidades WHERE ruc = ?";
$stmtRuc = $conn->prepare($sqlRuc);
$stmtRuc->bind_param("s", $ruc);
$stmtRuc->execute();
$stmtRuc->store_result();

if ($stmtRuc->num_rows > 0) {
    error_log("❌ RUC duplicado: $ruc");
    echo "❌ Ya existe una entidad con ese RUC.";
    return;
}

// 🛠️ Insertar entidad con creado_por
$sql = "INSERT INTO entidades (
            nombre, ruc, direccion, tipo_id, departamento_id, provincia_id, distrito_id,
            estado, fecha_creacion, creado_por
        ) VALUES (?, ?, ?, ?, ?, ?, ?, 'activo', NOW(), ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("❌ Error en prepare: " . $conn->error);
    echo "❌ Error SQL.";
    return;
}

if (!$stmt->bind_param("sssiiiis", $nombre, $ruc, $direccion, $tipo_id, $departamento_id, $provincia_id, $distrito_id, $creado_por)) {
    error_log("❌ Error en bind_param: " . $stmt->error);
    echo "❌ Error en bind_param.";
    return;
}

if ($stmt->execute()) {
    error_log("✅ Entidad creada correctamente con ID " . $stmt->insert_id);
    echo "ok";
} else {
    error_log("❌ Error al guardar: " . $stmt->error);
    echo "❌ Error al guardar.";
}
?>