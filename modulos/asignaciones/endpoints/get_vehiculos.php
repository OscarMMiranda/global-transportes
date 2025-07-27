<?php
header('Content-Type: application/json');
include '../../conexion.php';

$tipo = $_GET['tipo'] ?? null;
if (!in_array($tipo, ['tracto', 'carreta'])) {
  echo json_encode(['ok' => false, 'error' => 'Tipo inválido']);
  exit;
}

$sql = "SELECT id, placa FROM vehiculos WHERE tipo = ? AND estado = 'disponible' ORDER BY placa";
$stmt = $db->prepare($sql);
$stmt->execute([$tipo]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['ok' => true, 'data' => $rows]);
