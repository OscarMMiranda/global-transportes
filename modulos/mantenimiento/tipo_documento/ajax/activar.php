<?php
// archivo: /modulos/mantenimiento/tipo_documento/ajax/activar.php
// activar.php — Reactiva un tipo de documento por ID

// 1. Cargar configuración y conexión
$path = __DIR__ . '/../../../../includes/config.php';
if (!file_exists($path)) {
  die("❌ No se encontró el archivo de configuración: $path");
}
require_once $path;

// 2. Validar sesión
if (!isset($_SESSION['usuario_id'])) {
  die("❌ Sesión no iniciada.");
}

// 3. Validar parámetro
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
  die("❌ ID inválido.");
}

$id = (int) $_POST['id'];
$conn = getConnection();
if (!$conn) {
  die("❌ Error de conexión.");
}

// 4. Verificar existencia
$stmt = $conn->prepare("SELECT id FROM tipos_documento WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
  $stmt->close();
  die("❌ El tipo de documento no existe.");
}
$stmt->close();

// 5. Activar registro
$stmt = $conn->prepare("UPDATE tipos_documento SET estado = 1, fecha_actualizacion = NOW() WHERE id = ?");
$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
  $err = $stmt->error;
  $stmt->close();
  die("❌ Error al activar: $err");
}
$stmt->close();

// 6. Confirmación
echo "✅ Tipo de documento activado correctamente.";