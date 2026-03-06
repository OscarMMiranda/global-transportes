<?php
// archivo: /modulos/conductores/acciones/listar_empresas_simple.php

require_once __DIR__ . '/../../../includes/config.php';

header('Content-Type: application/json; charset=utf-8');

$conn = getConnection();

if (!$conn) {
    echo json_encode([
        'success' => false,
        'error'   => 'Error de conexión'
    ]);
    exit; 
}

$sql = "
    SELECT 
        id,
        razon_social
    FROM empresa
    ORDER BY razon_social ASC
";

$res = $conn->query($sql);

if (!$res) {
    echo json_encode([
        'success' => false,
        'error'   => 'Error SQL: ' . $conn->error
    ]);
    exit;
}

$data = [];

while ($row = $res->fetch_assoc()) {
    $data[] = [
        'id'   => (int)$row['id'],
        'nombre' => $row['razon_social']
    ];
}

echo json_encode([
    'success' => true,
    'data'    => $data
]);
exit;
