<?php
// archivo: /admin/users/ajax/ajax_eliminar_definitivo.php

session_start();
require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

// Depuración defensiva
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log_eliminar_definitivo.txt');
error_reporting(E_ALL);

// Validar sesión y rol
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
  echo json_encode(['estado' => 'error', 'mensaje' => 'Acceso no autorizado']);
  exit;
}

// Validar ID recibido
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
  echo json_encode(['estado' => 'error', 'mensaje' => 'ID inválido']);
  exit;
}

// Eliminar físicamente el usuario
$stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
  // Registrar historial
  $accion = "Eliminó definitivamente usuario ID $id";
  $ip     = $_SERVER['REMOTE_ADDR'];
  $admin  = $_SESSION['usuario'];

  $stmt_hist = $conn->prepare("
    INSERT INTO historial_bd (usuario, accion, tabla_afectada, ip_usuario)
    VALUES (?, ?, 'usuarios', ?)
  ");
  if ($stmt_hist) {
    $stmt_hist->bind_param("sss", $admin, $accion, $ip);
    $stmt_hist->execute();
    $stmt_hist->close();
  }

  echo json_encode([
    'estado'  => 'ok',
    'mensaje' => '✅ Usuario eliminado definitivamente'
  ]);
} else {
  echo json_encode([
    'estado'  => 'error',
    'mensaje' => '❌ Error al eliminar: ' . $conn->error
  ]);
}

$stmt->close();