<?php
// /modulos/ubigeo/acciones/listar_provincias.php
require_once __DIR__ . '/../../../includes/config.php';

header('Content-Type: application/json; charset=utf-8');

$departamento_id = isset($_GET['departamento_id']) ? intval($_GET['departamento_id']) : 0;

if ($departamento_id <= 0) {
    echo json_encode(array('success' => false, 'error' => 'Departamento inválido'));
    exit;
}

$conn = getConnection();
if (!$conn) {
    echo json_encode(array('success' => false, 'error' => 'Error de conexión'));
    exit;
}

$stmt = $conn->prepare("
    SELECT id, nombre 
    FROM provincias 
    WHERE departamento_id = ?
    ORDER BY nombre ASC
");
$stmt->bind_param("i", $departamento_id);
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
