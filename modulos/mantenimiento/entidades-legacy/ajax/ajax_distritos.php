<?php
// archivo: /modulos/mantenimiento/entidades/ajax/ajax_distritos.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

// 2. Encabezado HTML
header('Content-Type: text/html; charset=UTF-8');

$provinciaId = isset($_GET['provincia_id']) ? (int)$_GET['provincia_id'] : 0;

echo '<option value="">-- Seleccionar distrito --</option>';

if ($provinciaId > 0 && $conn) {
  $sql = "SELECT id, nombre FROM distritos WHERE provincia_id = ? ORDER BY nombre ASC";
  $stmt = $conn->prepare($sql);
  if ($stmt) {
    $stmt->bind_param("i", $provinciaId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
      printf('<option value="%d">%s</option>', $row['id'], htmlspecialchars($row['nombre']));
    }

    $stmt->close();
  }
}

$conn->close();
exit;