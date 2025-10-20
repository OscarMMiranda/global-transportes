<?php
	// 	archivo	: 	/modulos/mantenimiento/agencia_aduana/ajax/actualizar.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo '❌ Método no permitido';
  exit;
}

// 🔍 Validar ID
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0 || !$conn) {
  echo '❌ ID inválido o conexión fallida';
  exit;
}

// 📥 Sanitizar y validar campos
$nombre          = trim(isset($_POST['nombre']) ? $_POST['nombre'] : '');
$ruc             = trim(isset($_POST['ruc']) ? $_POST['ruc'] : '');
$direccion       = trim(isset($_POST['direccion']) ? $_POST['direccion'] : '');
$departamento_id = isset($_POST['departamento_id']) ? intval($_POST['departamento_id']) : 0;
$provincia_id    = isset($_POST['provincia_id']) ? intval($_POST['provincia_id']) : 0;
$distrito_id     = isset($_POST['distrito_id']) ? intval($_POST['distrito_id']) : 0;
$telefono        = trim(isset($_POST['telefono']) ? $_POST['telefono'] : '');
$correo_general  = trim(isset($_POST['correo_general']) ? $_POST['correo_general'] : '');
$contacto        = trim(isset($_POST['contacto']) ? $_POST['contacto'] : '');

// 🧪 Validación obligatoria
if ($nombre === '' || $ruc === '' || $departamento_id <= 0 || $provincia_id <= 0 || $distrito_id <= 0) {
  echo '❌ Campos obligatorios incompletos';
  exit;
}

// 🧠 Trazabilidad visual (solo para depuración)
error_log('📥 POST recibido: ' . print_r($_POST, true));
error_log("🔧 ID: $id | Nombre: $nombre | RUC: $ruc | Dir: $direccion | Dpto: $departamento_id | Prov: $provincia_id | Dist: $distrito_id");

// 🛠 Actualizar registro
$stmt = $conn->prepare("
  UPDATE agencias_aduanas SET
    nombre = ?, ruc = ?, direccion = ?, departamento_id = ?, provincia_id = ?, distrito_id = ?,
    telefono = ?, correo_general = ?, contacto = ?
  WHERE id = ?
");

if (!$stmt) {
  error_log('❌ Error al preparar la consulta: ' . $conn->error);
  echo '❌ Error al preparar la consulta';
  exit;
}

$stmt->bind_param(
  "sssiiisssi",
  $nombre,
  $ruc,
  $direccion,
  $departamento_id,
  $provincia_id,
  $distrito_id,
  $telefono,
  $correo_general,
  $contacto,
  $id
);

$ok = $stmt->execute();

if (!$ok) {
  error_log('❌ Error al ejecutar UPDATE: ' . $stmt->error);
}

$stmt->close();

if ($ok) {
  echo '✅ Cambios guardados correctamente';
} else {
  echo '❌ Error al guardar los cambios. Verifique los datos o revise el log.';
}