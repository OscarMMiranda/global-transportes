<?php
// archivo: /modulos/documentos/acciones/get_entidades.php

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

$response = [
    "ok" => true,
    "html" => '<option value="">Seleccione...</option>',
    "mensaje" => ""
];

if ($tipo == '') {
    echo json_encode($response);
    exit;
}

$map = [
    "vehiculo" => [
        "sql" => "SELECT id, placa AS nombre FROM vehiculos WHERE activo = 1 ORDER BY placa ASC"
    ],
    "conductor" => [
        "sql" => "SELECT id, CONCAT(nombres,' ',apellidos) AS nombre FROM conductores WHERE activo = 1 ORDER BY nombres ASC"
    ],
    "empleado" => [
        "sql" => "SELECT id, CONCAT(nombres,' ',apellidos) AS nombre FROM empleados WHERE estado = 'Activo' ORDER BY nombres ASC"
    ],
    "empresa" => [
        "sql" => "SELECT id, razon_social AS nombre FROM empresa ORDER BY razon_social ASC"
    ]
];

if (!isset($map[$tipo])) {
    $response["ok"] = false;
    $response["mensaje"] = "Tipo de entidad no válido.";
    echo json_encode($response);
    exit;
}

$sql = $map[$tipo]["sql"];

$res = $conn->query($sql);

if (!$res) {
    $response["ok"] = false;
    $response["mensaje"] = "Error SQL: " . $conn->error;
    echo json_encode($response);
    exit;
}

while ($row = $res->fetch_assoc()) {
    $response["html"] .= '<option value="'.$row['id'].'">'.$row['nombre'].'</option>';
}

echo json_encode($response);
exit;
