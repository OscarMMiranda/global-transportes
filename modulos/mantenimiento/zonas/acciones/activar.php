<?php
// archivo: acciones/activar.php
// propósito: reactivar una relación zona-distrito previamente desactivada

if (!isset($_GET['activar'])) {
  $_SESSION['error'] = '❌ ID no especificado para activar.';
  header('Location: index.php');
  exit;
}

$id = (int) $_GET['activar'];

require_once __DIR__ . '/../controllers/zonas_controller.php';

$stmt = $conn->prepare("UPDATE zonas SET estado = 1 WHERE id = ?");
if (!$stmt) {
  $_SESSION['error'] = "❌ Error al preparar activación: " . $conn->error;
  header('Location: index.php');
  exit;
}

$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
  $_SESSION['error'] = "❌ Error al ejecutar activación: " . $stmt->error;
  $stmt->close();
  header('Location: index.php');
  exit;
}

$stmt->close();
header("Location: index.php?msg=Distrito reactivado correctamente");
exit;