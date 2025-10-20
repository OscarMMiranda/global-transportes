<?php
// archivo  :   /modulos/mantenimiento/tipo_documento/ajax/editar.php

session_start();

// 1) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// 2) Cargar configuración y conexión
require_once __DIR__ . '/../../../../includes/config.php';
$conn = getConnection();

// 3) Validación de conexión
if (!$conn || !($conn instanceof mysqli)) {
  $_SESSION['error'] = "❌ Error de conexión con la base de datos.";
  header("Location: ../index.php");
  exit;
}

// 4) Sanitizar y validar entrada
$id          = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$nombre      = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
$categoria   = isset($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : 0;
$duracion    = isset($_POST['duracion_meses']) ? (int)$_POST['duracion_meses'] : 0;

$requiere_inicio      = isset($_POST['requiere_inicio']) ? 1 : 0;
$requiere_vencimiento = isset($_POST['requiere_vencimiento']) ? 1 : 0;
$requiere_archivo     = isset($_POST['requiere_archivo']) ? 1 : 0;
$codigo_interno       = isset($_POST['codigo_interno']) ? trim($_POST['codigo_interno']) : '';
$color_etiqueta       = isset($_POST['color_etiqueta']) ? trim($_POST['color_etiqueta']) : '#ffffff';
$icono                = isset($_POST['icono']) ? trim($_POST['icono']) : '';
$grupo                = isset($_POST['grupo']) ? trim($_POST['grupo']) : '';
$usuario_editor       = isset($_SESSION['usuario_id']) ? (int)$_SESSION['usuario_id'] : null;

// Validación básica
if ($id === 0 || $nombre === '' || $categoria === 0) {
  $_SESSION['error'] = "⚠️ Todos los campos obligatorios deben estar completos.";
  header("Location: ../index.php");
  exit;
}

// 5) Verificar existencia del registro y obtener versión actual
$check = $conn->prepare("SELECT version, estado FROM tipos_documento WHERE id = ?");
if (!$check) {
  $_SESSION['error'] = "❌ Error al preparar verificación.";
  header("Location: ../index.php");
  exit;
}
$check->bind_param("i", $id);
$check->execute();
$check->bind_result($version_actual, $estado_anterior);
if (!$check->fetch()) {
  $_SESSION['error'] = "❌ El tipo de documento no existe.";
  $check->close();
  header("Location: ../index.php");
  exit;
}
$check->close();

// 6) Preparar consulta segura
$stmt = $conn->prepare("
  UPDATE tipos_documento SET
    categoria_id = ?, nombre = ?, descripcion = ?, duracion_meses = ?,
    requiere_inicio = ?, requiere_vencimiento = ?, requiere_archivo = ?,
    codigo_interno = ?, color_etiqueta = ?, icono = ?, grupo = ?,
    version = ?, estado_anterior = ?, usuario_editor = ?, fecha_actualizacion = NOW()
  WHERE id = ?
");

if (!$stmt) {
  $_SESSION['error'] = "❌ Error al preparar la actualización.";
  header("Location: ../index.php");
  exit;
}

$nueva_version = $version_actual + 1;

$stmt->bind_param(
  "issiiiiisssiiii",
  $categoria, $nombre, $descripcion, $duracion,
  $requiere_inicio, $requiere_vencimiento, $requiere_archivo,
  $codigo_interno, $color_etiqueta, $icono, $grupo,
  $nueva_version, $estado_anterior, $usuario_editor, $id
);

// 7) Ejecutar y verificar
if ($stmt->execute()) {
  $_SESSION['msg'] = "✅ Tipo de documento actualizado correctamente.";
} else {
  $_SESSION['error'] = "❌ Error al actualizar el registro.";
}

// 8) Cerrar y redirigir
$stmt->close();
$conn->close();
header("Location: ../index.php");
exit;