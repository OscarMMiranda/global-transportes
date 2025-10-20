<?php
// archivo: /modulos/mantenimiento/tipo_documento/ajax/desactivar.php
// desactivar.php — Desactiva un tipo de documento por ID

$path = __DIR__ . '/../../../../includes/config.php';
if (!file_exists($path)) {
  die("❌ No se encontró el archivo de configuración: $path");
}
require_once $path;

if (!isset($_SESSION['usuario_id'])) {
  die("❌ Sesión no iniciada.");
}

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
  die("❌ ID inválido.");
}

$id = (int) $_POST['id'];
$conn = getConnection();
if (!$conn) {
  die("❌ Error de conexión.");
}

// Verificar existencia
$stmt = $conn->prepare("SELECT id FROM tipos_documento WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
  $stmt->close();
  die("❌ El tipo de documento no existe.");
}
$stmt->close();

// Desactivar registro
$stmt = $conn->prepare("UPDATE tipos_documento SET estado = 0, fecha_actualizacion = NOW() WHERE id = ?");
$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
  $err = $stmt->error;
  $stmt->close();
  die("❌ Error al desactivar: $err");
}
$stmt->close();

echo "✅ Tipo de documento desactivado correctamente.";