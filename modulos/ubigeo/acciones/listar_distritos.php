<?php
// /modulos/ubigeo/acciones/listar_distritos.php
require_once __DIR__ . '/../../../includes/config.php';

header('Content-Type: application/json; charset=utf-8');

$provincia_id = isset($_GET['provincia_id']) ? intval($_GET['provincia_id']) : 0;

if ($provincia_id <= 0) {
    echo json_encode(array('success' => false, 'error' => 'Provincia inválida'));
    exit;
}

$conn = getConnection();
if (!$conn) {
    echo json_encode(array('success' => false, 'error' => 'Error de conexión'));
    exit;
}

$stmt = $conn->prepare("
    SELECT id, nombre 
    FROM distritos 
    WHERE provincia_id = ?
    ORDER BY nombre ASC
");
$stmt->bind_param("i", $provincia_id);
$stmt->execute();
$res = $stmt->get_result();

$data = array();
while ($row = $res->fetch_assoc()) {
    $data[] = array(
        'id'     => (int)$row['id'],
        'nombre' => $row['nombre']
    );
}

$stmt->close();

echo json_encode(array(
    'success' => true,
    'data'    => $data
));
