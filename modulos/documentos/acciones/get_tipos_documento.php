<?php
// archivo: /modulos/documentos/acciones/get_tipos_documento.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

$response = [
    "ok" => true,
    "html" => '<option value="">Seleccione...</option>',
    "mensaje" => ""
];

$map = [
    "vehiculo" => 1,
    "conductor" => 2,
    "empresa" => 3
];

if (!isset($map[$tipo])) {
    echo json_encode($response);
    exit;
}

$categoria = $map[$tipo];

$sql = "SELECT id, nombre 
        FROM tipos_documento 
        WHERE estado = 1 AND categoria_id = ?
        ORDER BY orden_visual ASC, nombre ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $categoria);

if (!$stmt->execute()) {
    $response["ok"] = false;
    $response["mensaje"] = "Error SQL: " . $conn->error;
    echo json_encode($response);
    exit;
}

$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    $response["html"] .= '<option value="'.$row['id'].'">'.$row['nombre'].'</option>';
}

echo json_encode($response);
exit;
