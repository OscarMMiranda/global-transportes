<?php
// 📄 Archivo: tipos.php — Carga dinámica de tipos de lugar para formularios

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');
error_log("🚦 Entrando a tipos.php");

// 🔌 Conexión
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

if (!is_object($conn) || get_class($conn) !== 'mysqli') {
    error_log("❌ Error de conexión en tipos.php");
    echo '<option value="">Error de conexión</option>';
    exit;
}

// 🧠 Valor seleccionado (opcional)
$selected_id = isset($_GET['selected']) ? intval($_GET['selected']) : 0;

// 🔍 Consulta segura
$sql = "SELECT id, nombre FROM tipo_lugares WHERE estado = 'activo' ORDER BY nombre ASC";
$res = $conn->query($sql);

if (!$res) {
    error_log("❌ Error SQL en tipos.php: " . $conn->error);
    echo '<option value="">Error al consultar tipos</option>';
    exit;
}

if ($res->num_rows === 0) {
    error_log("⚠️ Sin registros en tipo_lugares");
    echo '<option value="">Sin tipos disponibles</option>';
    exit;
}

// 🧩 Renderizado HTML puro
echo '<option value="">-- Seleccionar tipo --</option>';
while ($row = $res->fetch_assoc()) {
    $id     = intval($row['id']);
    $nombre = htmlspecialchars($row['nombre']);
    $selected = ($id === $selected_id) ? ' selected' : '';
    echo "<option value=\"$id\"$selected>$nombre</option>";
}
?>