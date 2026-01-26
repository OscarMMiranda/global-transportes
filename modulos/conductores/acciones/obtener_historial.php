<?php
// archivo: /modulos/conductores/acciones/obtener_historial.php

require_once __DIR__ . '/../../../includes/config.php';

header('Content-Type: application/json; charset=utf-8');

$conn = getConnection();
if (!$conn) {
    echo json_encode(['success' => false, 'error' => 'Conexión fallida']);
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'ID inválido']);
    exit;
}

$sql = "SELECT * FROM conductores_historial 
        WHERE id_registro = ?
        ORDER BY fecha_cambio DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {

    // Decodificar cambios
    $row['cambios_json'] = json_decode($row['cambios_json'], true);

    // Normalizar foto actual
    if (!empty($row['foto'])) {
        $row['foto'] = "/uploads/conductores/" . basename($row['foto']);
    } else {
        $row['foto'] = null;
    }

    // Normalizar foto anterior
    if (!empty($row['ruta_foto_anterior'])) {
        $row['ruta_foto_anterior'] = "/uploads/conductores/historial/" . basename($row['ruta_foto_anterior']);
    } else {
        $row['ruta_foto_anterior'] = null;
    }

    $data[] = $row;
}

echo json_encode([
    'success' => true,
    'historial' => $data
]);
