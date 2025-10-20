<?php
// buscar.php — Devuelve datos completos de un lugar por ID

header('Content-Type: application/json');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

if (!($conn instanceof mysqli)) {
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

$sql = "SELECT l.id, l.nombre, l.direccion, l.tipo_id, l.distrito_id,
               d.provincia_id, p.departamento_id
        FROM lugares l
        LEFT JOIN distritos d ON l.distrito_id = d.id
        LEFT JOIN provincias p ON d.provincia_id = p.id
        WHERE l.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$lugar = $result->fetch_assoc();

if (!$lugar) {
    echo json_encode(['error' => 'Lugar no encontrado']);
    exit;
}

// ✅ Validación defensiva
foreach (['id', 'nombre', 'direccion', 'tipo_id', 'distrito_id', 'provincia_id', 'departamento_id'] as $campo) {
    if (!isset($lugar[$campo])) {
        echo json_encode(['error' => "Campo faltante: $campo"]);
        exit;
    }
}

echo json_encode($lugar);