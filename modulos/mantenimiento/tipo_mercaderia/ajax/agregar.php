<?php
// archivo: /modulos/mantenimiento/tipo_mercaderia/ajax/agregar.php

session_start();

// 1) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// 2) Cargar configuración y conexión
require_once __DIR__ . '/../../../../includes/config.php';
$conn = getConnection();

// 3) Validación defensiva
if (!isset($conn) || !($conn instanceof mysqli)) {
  $_SESSION['error'] = "❌ Error de conexión con la base de datos.";
  header("Location: ../index.php");
  exit;
}

// 4) Sanitizar y validar entrada
$nombre      = isset($_POST['nombre'])      ? trim($_POST['nombre'])      : '';
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';

if ($nombre === '') {
  $_SESSION['error'] = "⚠️ El nombre es obligatorio.";
  header("Location: ../index.php");
  exit;
}

// 5) Preparar consulta segura
$stmt = $conn->prepare("INSERT INTO tipos_mercaderia (nombre, descripcion, estado) VALUES (?, ?, 1)");

if (!$stmt) {
  $_SESSION['error'] = "❌ Error al preparar la consulta.";
  header("Location: ../index.php");
  exit;
}

$stmt->bind_param("ss", $nombre, $descripcion);

// 6) Ejecutar y verificar
if ($stmt->execute()) {
  $_SESSION['msg'] = "✅ Tipo de mercadería agregado correctamente.";
} else {
  $_SESSION['error'] = "❌ Error al guardar el registro.";
}

// 7) Cerrar y redirigir
$stmt->close();
$conn->close();
header("Location: ../index.php");
exit;