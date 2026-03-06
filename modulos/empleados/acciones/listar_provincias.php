<?php
// /modulos/ubigeo/acciones/listar_provincias.php

require_once __DIR__ . '/../../../includes/config.php';

header('Content-Type: application/json; charset=utf-8');

$conn = getConnection();
if (!$conn) {
    echo json_encode([
        'success' => false,
        'error' => 'Error de conexión a la base de datos'
    ]);
    exit;
}

$departamento_id = isset($_GET['departamento_id']) ? intval($_GET['departamento_id']) : 0;

$sql = "SELECT id, nombre 
        FROM provincias 
        WHERE departamento_id = $departamento_id
        ORDER BY nombre ASC";

$result = $conn->query($sql);

$data = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id'     => (int)$row['id'],
            'nombre' => $row['nombre']
        ];
    }
}

echo json_encode([
    'success' => true,
    'data'    => $data
]);
