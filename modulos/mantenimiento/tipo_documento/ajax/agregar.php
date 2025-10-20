<?php
// archivo: ajax/agregar.php

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
$categoria   = isset($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : 0;
$nombre      = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
$duracion    = isset($_POST['duracion_meses']) ? (int)$_POST['duracion_meses'] : 0;

$requiere_inicio      = isset($_POST['requiere_inicio']) ? 1 : 0;
$requiere_vencimiento = isset($_POST['requiere_vencimiento']) ? 1 : 0;
$requiere_archivo     = isset($_POST['requiere_archivo']) ? 1 : 0;
$codigo_interno       = isset($_POST['codigo_interno']) ? trim($_POST['codigo_interno']) : '';
$color_etiqueta       = isset($_POST['color_etiqueta']) ? trim($_POST['color_etiqueta']) : '#ffffff';
$icono                = isset($_POST['icono']) ? trim($_POST['icono']) : '';
$grupo                = isset($_POST['grupo']) ? trim($_POST['grupo']) : '';
$estado               = 1;
$version              = 1;
$origen               = 'manual';
$usuario_creador      = isset($_SESSION['usuario_id']) ? (int)$_SESSION['usuario_id'] : null;

if ($nombre === '' || $categoria === 0) {
  $_SESSION['error'] = "⚠️ El nombre y la categoría son obligatorios.";
  header("Location: ../index.php");
  exit;
}

// 5) Preparar consulta segura
$stmt = $conn->prepare("
  INSERT INTO tipos_documento (
    categoria_id, nombre, descripcion, estado, duracion_meses,
    requiere_inicio, requiere_vencimiento, requiere_archivo,
    codigo_interno, color_etiqueta, icono, grupo,
    version, origen, usuario_creador, fecha_creacion
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
");

if (!$stmt) {
  $_SESSION['error'] = "❌ Error al preparar la consulta.";
  header("Location: ../index.php");
  exit;
}

$stmt->bind_param(
  "issiiiiissssisi",
  $categoria, $nombre, $descripcion, $estado, $duracion,
  $requiere_inicio, $requiere_vencimiento, $requiere_archivo,
  $codigo_interno, $color_etiqueta, $icono, $grupo,
  $version, $origen, $usuario_creador
);

// 6) Ejecutar y verificar
if ($stmt->execute()) {
  $_SESSION['msg'] = "✅ Tipo de documento agregado correctamente.";
} else {
  $_SESSION['error'] = "❌ Error al guardar el registro.";
}

// 7) Cerrar y redirigir
$stmt->close();
$conn->close();
header("Location: ../index.php");
exit;