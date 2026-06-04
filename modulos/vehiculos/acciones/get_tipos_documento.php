<?php
// archivo: /modulos/vehiculos/acciones/get_tipos_documento.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

header('Content-Type: application/json; charset=utf-8');

// SOLO DOCUMENTOS PARA VEHÍCULOS
$sql = "SELECT id, nombre
        FROM tipos_documento
        WHERE estado = 1
        AND categoria_id = 1
        ORDER BY nombre";

$res = $conn->query($sql);

if (!$res) {
    echo json_encode([
        "success" => false,
        "error" => $conn->error,
        "sql" => $sql
    ]);
    exit;
}

$data = array();

while ($t = $res->fetch_assoc()) {
    $data[] = $t;
}

echo json_encode([
    "success" => true,
    "data" => $data
]);
