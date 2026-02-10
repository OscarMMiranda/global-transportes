<?php
// archivo: /modulos/asistencias/acciones/obtener_tipos.php
header('Content-Type: application/json');

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

// Obtener todos los tipos de asistencia
$sql = "SELECT id, codigo, descripcion FROM asistencia_tipos ORDER BY id ASC";
$res = $conn->query($sql);

$lista = [];

if ($res) {
    while ($row = $res->fetch_assoc()) {
        $lista[] = $row;
    }
}

echo json_encode([
    'ok' => true,
    'data' => $lista
]);
