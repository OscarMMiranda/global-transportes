<?php
// archivo: /modulos/asignaciones/api/historial.php

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../model/asignaciones.php';

$conn = getConnection();

header('Content-Type: application/json');

// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

$id = intval($_GET['id']);

// Obtener historial desde el modelo
$data = obtenerHistorial($conn, $id);

// Respuesta
echo json_encode($data);
