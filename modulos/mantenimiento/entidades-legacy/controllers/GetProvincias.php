<?php
// archivo: /modulos/mantenimiento/entidades/controllers/GetProvincias.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

header('Content-Type: text/html; charset=utf-8');

$departamentoId = isset($_GET['departamento_id']) ? (int)$_GET['departamento_id'] : 0;
error_log("📥 GetProvincias.php llamado con departamento_id = $departamentoId");

if ($departamentoId <= 0 || !$conn) {
  echo '<option value="">-- Seleccionar --</option>';
  exit;
}

$sql = "SELECT id, nombre FROM provincias WHERE departamento_id = ? ORDER BY nombre ASC";
$stmt = $conn->prepare($sql);
if (!$stmt) {
  error_log("❌ Error al preparar consulta de provincias");
  echo '<option value="">Error al preparar consulta</option>';
  exit;
}

$stmt->bind_param("i", $departamentoId);
$stmt->execute();
$result = $stmt->get_result();

echo '<option value="">-- Seleccionar --</option>';
while ($row = $result->fetch_assoc()) {
  echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['nombre']) . '</option>';
}

$stmt->close();
$conn->close();