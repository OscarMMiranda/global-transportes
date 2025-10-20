<?php
	// archivo	:	/modulos/mantenimiento/agencia_aduana/ajax/provincias_por_departamento.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0 || !$conn) {
  echo json_encode([]);
  exit;
}

$sql = "SELECT id, nombre FROM provincias WHERE departamento_id = $id ORDER BY nombre ASC";
$res = $conn->query($sql);
$data = [];

while ($row = $res->fetch_assoc()) {
  $data[] = $row;
}

echo json_encode($data);