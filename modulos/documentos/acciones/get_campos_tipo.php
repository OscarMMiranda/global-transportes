<?php
// archivo: /modulos/documentos/acciones/get_campos_tipo.php

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

$tipo = isset($_GET['tipo']) ? intval($_GET['tipo']) : 0;

$response = [
    "ok" => true,
    "html" => "",
    "mensaje" => ""
];

if ($tipo <= 0) {
    echo json_encode($response);
    exit;
}

$sql = "SELECT nombre, requiere_inicio, requiere_vencimiento, requiere_archivo 
        FROM tipos_documento 
        WHERE id = ? LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tipo);

if (!$stmt->execute()) {
    $response["ok"] = false;
    $response["mensaje"] = "Error SQL: " . $conn->error;
    echo json_encode($response);
    exit;
}

$res = $stmt->get_result();
$tipoDoc = $res->fetch_assoc();

if (!$tipoDoc) {
    $response["ok"] = false;
    $response["mensaje"] = "Tipo de documento no encontrado.";
    echo json_encode($response);
    exit;
}

$html = "";

// Fecha inicio
if ($tipoDoc['requiere_inicio'] == 1) {
    $html .= '
        <div class="mb-3">
            <label class="form-label">Fecha de inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" required>
        </div>';
}

// Fecha vencimiento
if ($tipoDoc['requiere_vencimiento'] == 1) {
    $html .= '
        <div class="mb-3">
            <label class="form-label">Fecha de vencimiento</label>
            <input type="date" name="fecha_vencimiento" class="form-control" required>
        </div>';
}

// Archivo
if ($tipoDoc['requiere_archivo'] == 1) {
    $html .= '
        <div class="mb-3">
            <label class="form-label">Archivo</label>
            <input type="file" name="archivo" class="form-control" required>
        </div>';
}

$response["html"] = $html;

echo json_encode($response);
exit;
