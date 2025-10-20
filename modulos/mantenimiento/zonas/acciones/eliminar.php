<?php
// archivo: acciones/eliminar.php
// propósito: desactivar una ruta entre distritos

session_start();

if (!isset($_GET['eliminar'])) {
  $_SESSION['error'] = '❌ ID no especificado para eliminar.';
  header('Location: ../index.php');
  exit;
}

$id = (int) $_GET['eliminar'];

require_once __DIR__ . '/../controllers/zonas_controller.php';

$stmt = $conn->prepare("UPDATE zonas SET estado = 0 WHERE id = ?");
if (!$stmt) {
  $_SESSION['error'] = "❌ Error al preparar eliminación: " . $conn->error;
  header('Location: ../index.php');
  exit;
}

$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
  $_SESSION['error'] = "❌ Error al ejecutar eliminación: " . $stmt->error;
  $stmt->close();
  header('Location: ../index.php');
  exit;
}

$stmt->close();
header("Location: ../index.php?msg=Ruta desactivada correctamente");
exit;