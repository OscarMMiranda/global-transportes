<?php
// archivo: /modulos/conductores/acciones/ver.php

require_once '../../../includes/config.php';
require_once '../controllers/conductores_controller.php';

header('Content-Type: application/json');

$conn = getConnection();

// Validar ID recibido por POST
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

// Consultar conductor
$conductor = obtenerConductor($conn, $id);

// Validar resultado
if (!$conductor || !isset($conductor['id'])) {
    http_response_code(404);
    echo json_encode(['error' => 'Conductor no encontrado']);
    exit;
}

// Devolver datos en JSON
echo json_encode([
    'id' => $conductor['id'],
    'nombres' => $conductor['nombres'],
    'apellidos' => $conductor['apellidos'],
    'dni' => $conductor['dni'],
    'licencia_conducir' => $conductor['licencia_conducir'],
    'telefono' => $conductor['telefono'],
    'correo' => $conductor['correo'],
    'direccion' => $conductor['direccion'],
    'activo' => $conductor['activo'],
    'foto' => $conductor['foto']
]);