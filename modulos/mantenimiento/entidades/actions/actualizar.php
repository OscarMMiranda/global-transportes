<?php
// 📄 archivo: actualizar.php — edición solo si el estado es activo y datos válidos (PHP 5.6)

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');
error_log("🚦 Entrando a actualizar.php");
error_log("📨 POST recibido: " . json_encode($_POST));

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();
if (!($conn instanceof mysqli)) {
    error_log("❌ Error de conexión");
    echo "❌ Error de conexión.";
    return;
}

// 🔍 Validar ID
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    error_log("❌ ID inválido");
    echo "❌ ID inválido.";
    return;
}

// 🧹 Validar y limpiar datos
$nombre      = isset($_POST['nombre'])      ? trim($_POST['nombre'])      : '';
$ruc         = isset($_POST['ruc'])         ? trim($_POST['ruc'])         : '';
$direccion   = isset($_POST['direccion'])   ? trim($_POST['direccion'])   : '';
$distrito_id = isset($_POST['distrito_id']) ? intval($_POST['distrito_id']) : 0;
$tipo_id     = isset($_POST['tipo_id'])     ? intval($_POST['tipo_id'])     : 0;

error_log("🧪 Tipos de datos:");
error_log("nombre: " . gettype($nombre));
error_log("ruc: " . gettype($ruc));
error_log("direccion: " . gettype($direccion));
error_log("distrito_id: " . gettype($distrito_id));
error_log("tipo_id: " . gettype($tipo_id));
error_log("id: " . gettype($id));

if (!$nombre || !$ruc || $distrito_id <= 0 || $tipo_id <= 0) {
    error_log("❌ Datos incompletos");
    echo "❌ Datos incompletos.";
    return;
}

// 🔍 Obtener registro actual (sin get_result)
$sqlActual = "SELECT estado FROM entidades WHERE id = ?";
$stmtActual = $conn->prepare($sqlActual);
$stmtActual->bind_param("i", $id);
$stmtActual->execute();
$stmtActual->store_result();

if ($stmtActual->num_rows === 0) {
    error_log("❌ Entidad no encontrada");
    echo "❌ Entidad no encontrada.";
    return;
}

$stmtActual->bind_result($estadoActual);
$stmtActual->fetch();

$estadoActual = strtolower(trim($estadoActual));
if ($estadoActual !== 'activo') {
    error_log("❌ Entidad inactiva, no editable");
    echo "❌ No se puede editar una entidad inactiva.";
    return;
}

// 🛠️ Actualizar entidad
$sql = "UPDATE entidades SET 
            nombre = ?, 
            ruc = ?, 
            direccion = ?, 
            distrito_id = ?, 
            tipo_id = ?, 
            fecha_modificacion = NOW()
        WHERE id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("❌ Error en prepare: " . $conn->error);
    echo "❌ Error en prepare.";
    return;
}

if (!$stmt->bind_param("sssiii", $nombre, $ruc, $direccion, $distrito_id, $tipo_id, $id)) {
    error_log("❌ Error en bind_param: " . $stmt->error);
    echo "❌ Error en bind_param.";
    return;
}

if ($stmt->execute()) {
    error_log("✅ Actualización exitosa para ID $id");
    echo "ok";
} else {
    error_log("❌ Error al actualizar: " . $stmt->error);
    echo "❌ Error al actualizar.";
}
?>