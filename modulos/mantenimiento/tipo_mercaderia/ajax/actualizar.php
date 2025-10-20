<?php
// archivo: actualizar.php

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
$id          = isset($_POST['id'])          ? intval($_POST['id']) : 0;
$nombre      = isset($_POST['nombre'])      ? trim($_POST['nombre']) : '';
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';

if (!$id || !$nombre || !$descripcion || !($conn instanceof mysqli)) {
  $_SESSION['error'] = "❌ Datos incompletos o conexión fallida.";
  header("Location: ../index.php");
  exit;
}

// 4) Preparar y ejecutar UPDATE
$stmt = $conn->prepare("UPDATE tipos_mercaderia SET nombre = ?, descripcion = ? WHERE id = ?");
if ($stmt) {
  $stmt->bind_param("ssi", $nombre, $descripcion, $id);
  if ($stmt->execute()) {
    $_SESSION['msg'] = "✅ Registro actualizado correctamente.";
  } else {
    $_SESSION['error'] = "❌ Error al actualizar: " . $stmt->error;
  }
  $stmt->close();
} else {
  $_SESSION['error'] = "❌ Error al preparar consulta: " . $conn->error;
}

// 5) Redirigir al módulo principal
header("Location: ../index.php");
exit;