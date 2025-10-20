<?php
	// archivo	:	/modulos/mantenimiento/agencia_aduana/ajax/distritos_por_provincia.php
	
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0 || !$conn) {
  echo json_encode([]);
  exit;
}

$sql = "SELECT id, nombre FROM distritos WHERE provincia_id = $id ORDER BY nombre ASC";
$res = $conn->query($sql);
$data = [];

if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);