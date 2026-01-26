<?php
// /modulos/ubigeo/acciones/listar_departamentos.php
require_once __DIR__ . '/../../../includes/config.php';

header('Content-Type: application/json; charset=utf-8');

$conn = getConnection();
if (!$conn) {
    echo json_encode(array('success' => false, 'error' => 'Error de conexión'));
    exit;
}

$sql = "SELECT id, nombre FROM departamentos ORDER BY nombre ASC";
$result = $conn->query($sql);

$data = array();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            'id'     => (int)$row['id'],
            'nombre' => $row['nombre']
        );
    }
}

echo json_encode(array(
    'success' => true,
    'data'    => $data
));
