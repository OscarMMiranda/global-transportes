<?php
// 🔐 Blindaje extremo y trazabilidad
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log_store.txt');

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

session_start();
$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'desconocido';
$ip = $_SERVER['REMOTE_ADDR'];

// 📦 Capturar y validar datos
$nombre        = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$ruc           = isset($_POST['ruc']) ? trim($_POST['ruc']) : '';
$direccion     = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';
$departamento  = isset($_POST['departamento']) ? intval($_POST['departamento']) : 0;
$provincia     = isset($_POST['provincia']) ? intval($_POST['provincia']) : 0;
$distrito      = isset($_POST['distrito']) ? intval($_POST['distrito']) : 0;
$observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : '';

// 🧠 Validación defensiva
if ($distrito <= 0) {
    error_log("❌ store.php: distrito_id inválido recibido: " . json_encode($_POST));
    echo '<div class="alert alert-danger">❌ Distrito inválido. No se puede registrar la agencia.</div>';
    return;
}

// 🔍 Validar existencia del distrito
$sqlCheck = "SELECT id FROM distritos WHERE id = $distrito LIMIT 1";
$resCheck = $conn->query($sqlCheck);
if (!$resCheck || $resCheck->num_rows === 0) {
    error_log("❌ store.php: distrito_id no existe en la tabla distritos: $distrito");
    echo '<div class="alert alert-danger">❌ El distrito seleccionado no existe. Verifica la selección.</div>';
    return;
}

// ✅ Insertar agencia
$sqlInsert = "INSERT INTO agencias_aduanas (
    nombre, ruc, direccion, departamento_id, provincia_id, distrito_id,
    usuario_creacion, ip_registro, observaciones, eliminado
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";

$stmt = $conn->prepare($sqlInsert);
if (!$stmt) {
    error_log("❌ store.php: error al preparar el INSERT - " . $conn->error);
    echo '<div class="alert alert-danger">❌ Error interno al preparar el registro.</div>';
    return;
}

$stmt->bind_param("sssiiisss", $nombre, $ruc, $direccion, $departamento, $provincia, $distrito, $usuario, $ip, $observaciones);

if ($stmt->execute()) {
    error_log("✅ store.php: agencia registrada correctamente - ID generado: " . $stmt->insert_id);
    echo '<div class="alert alert-success">✅ Agencia registrada correctamente.</div>';
} else {
    error_log("❌ store.php: error al ejecutar el INSERT - " . $stmt->error);
    echo '<div class="alert alert-danger">❌ Error al insertar: ' . htmlspecialchars($stmt->error) . '</div>';
}

$stmt->close();
?>