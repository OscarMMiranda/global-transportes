<?php
// archivo: guardar.php

// 🔐 Blindaje extremo y trazabilidad
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo '❌ Método no permitido';
  exit;
}

// 📥 Sanitizar y validar campos
$nombre          = trim($_POST['nombre'] ?? '');
$ruc             = trim($_POST['ruc'] ?? '');
$direccion       = trim($_POST['direccion'] ?? '');
$departamento_id = intval($_POST['departamento_id'] ?? 0);
$provincia_id    = intval($_POST['provincia_id'] ?? 0);
$distrito_id     = intval($_POST['distrito_id'] ?? 0);
$telefono        = trim($_POST['telefono'] ?? '');
$correo_general  = trim($_POST['correo_general'] ?? '');
$contacto        = trim($_POST['contacto'] ?? '');

// 🧠 Trazabilidad visual
error_log("📥 POST recibido: " . print_r($_POST, true));
error_log("📦 Dirección POST: '" . $_POST['direccion'] . "'");
error_log("📦 Dirección final antes de blindaje: '" . $direccion . "'");

// 🛡️ Blindaje de dirección
if ($direccion === '0' || $direccion === '') {
  error_log("⚠️ Dirección inválida en creación. Valor recibido: '$direccion'");
  $direccion = '[SIN DIRECCIÓN]';
}

// 🧪 Validación obligatoria
if ($nombre === '' || $ruc === '' || $departamento_id <= 0 || $provincia_id <= 0 || $distrito_id <= 0) {
  echo '❌ Campos obligatorios incompletos';
  exit;
}

// 🔍 Validar que el distrito pertenezca a la provincia
$sql = "SELECT provincia_id FROM distritos WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
  error_log('❌ Error al preparar validación de distrito: ' . $conn->error);
  echo '❌ Error interno al validar distrito';
  exit;
}
$stmt->bind_param("i", $distrito_id);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();

if ($res->num_rows === 0) {
  echo '❌ El distrito no existe.';
  exit;
}

$row = $res->fetch_assoc();
$provincia_real = intval($row['provincia_id']);

if ($provincia_real !== $provincia_id) {
  error_log("❌ Distrito $distrito_id pertenece a provincia $provincia_real, no a $provincia_id");
  echo '❌❌ El distrito no pertenece a la provincia seleccionada.';
  exit;
}

// 🔍 Validar que la provincia pertenezca al departamento
$sql = "SELECT departamento_id FROM provincias WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
  error_log('❌ Error al preparar validación de provincia: ' . $conn->error);
  echo '❌ Error interno al validar provincia';
  exit;
}
$stmt->bind_param("i", $provincia_id);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();

if ($res->num_rows === 0) {
  echo '❌ La provincia no existe.';
  exit;
}

$row = $res->fetch_assoc();
$departamento_real = intval($row['departamento_id']);

if ($departamento_real !== $departamento_id) {
  error_log("❌ Provincia $provincia_id pertenece a departamento $departamento_real, no a $departamento_id");
  echo '❌ La provincia no pertenece al departamento seleccionado.';
  exit;
}

// 🛠 Insertar nueva agencia
$stmt = $conn->prepare("
  INSERT INTO agencias_aduanas (
    nombre, ruc, direccion, departamento_id, provincia_id, distrito_id,
    telefono, correo_general, contacto, estado
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
");

if (!$stmt) {
  error_log('❌ Error al preparar INSERT: ' . $conn->error);
  echo '❌ Error al preparar el registro';
  exit;
}

$stmt->bind_param(
  "sssiiisss",
  $nombre,
  $ruc,
  $direccion,
  $departamento_id,
  $provincia_id,
  $distrito_id,
  $telefono,
  $correo_general,
  $contacto
);

$ok = $stmt->execute();

if (!$ok) {
  error_log('❌ Error al insertar agencia: ' . $stmt->error);
}

$stmt->close();

if ($ok) {
  echo '✅ Agencia registrada correctamente';
} else {
  echo '❌ Error al guardar la agencia. Verifique los datos o revise el log.';
}