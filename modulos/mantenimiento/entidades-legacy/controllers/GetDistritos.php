<?php
// archivo: /modulos/mantenimiento/entidades/controllers/GetDistritos.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

header('Content-Type: text/html; charset=utf-8');

$provinciaId = isset($_GET['provincia_id']) ? (int)$_GET['provincia_id'] : 0;

if ($provinciaId <= 0 || !$conn) {
  echo '<option value="">-- Seleccionar --</option>';
  exit;
}

$sql = "SELECT id, nombre FROM distritos WHERE provincia_id = ? ORDER BY nombre ASC";
$stmt = $conn->prepare($sql);
if (!$stmt) {
  echo '<option value="">Error al preparar consulta</option>';
  exit;
}

$stmt->bind_param("i", $provinciaId);
$stmt->execute();
$result = $stmt->get_result();

echo '<option value="">-- Seleccionar --</option>';
while ($row = $result->fetch_assoc()) {
  echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['nombre']) . '</option>';
}

$stmt->close();
$conn->close();