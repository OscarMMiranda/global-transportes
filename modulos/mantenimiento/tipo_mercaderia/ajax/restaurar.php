<?php
// archivo: restaurar.php

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
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if (!$id || !($conn instanceof mysqli)) {
  $_SESSION['error'] = "❌ ID inválido o conexión fallida.";
  header("Location: ../index.php");
  exit;
}

// 4) Restaurar registro (estado = 1)
$stmt = $conn->prepare("UPDATE tipos_mercaderia SET estado = 1 WHERE id = ?");
if ($stmt) {
  $stmt->bind_param("i", $id);
  if ($stmt->execute()) {
    $_SESSION['msg'] = "✅ Registro restaurado correctamente.";
  } else {
    $_SESSION['error'] = "❌ Error al restaurar: " . $stmt->error;
  }
  $stmt->close();
} else {
  $_SESSION['error'] = "❌ Error al preparar consulta: " . $conn->error;
}

// 5) Redirigir al módulo principal
header("Location: ../index.php");
exit;