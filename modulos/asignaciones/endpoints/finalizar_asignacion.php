<?php
header('Content-Type: application/json');
include '../../conexion.php'; // Ajusta si tu archivo de conexión está en otra ruta

// Obtener el ID de la asignación
$id = $_GET['id'] ?? $_POST['id'] ?? null;
if (!$id || !is_numeric($id)) {
  echo json_encode(['ok' => false, 'error' => 'ID inválido']);
  exit;
}

// Verificar si existe y está activa
$sql = "SELECT estado FROM asignaciones WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$id]);
$asignacion = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$asignacion) {
  echo json_encode(['ok' => false, 'error' => 'Asignación no encontrada']);
  exit;
}

if ($asignacion['estado'] !== 'activo') {
  echo json_encode(['ok' => false, 'error' => 'La asignación ya está finalizada']);
  exit;
}

// Actualizar: marcar como finalizada
$update = $db->prepare("UPDATE asignaciones SET estado = 'finalizado', fecha_fin = CURDATE() WHERE id = ?");
$update->execute([$id]);

echo json_encode(['ok' => true]);
