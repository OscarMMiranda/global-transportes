<?php
// archivo: /modulos/conductores/acciones/listar.php

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../controllers/conductores_controller.php';

header('Content-Type: application/json; charset=utf-8');

$conn = getConnection();
if (!$conn) {
    echo json_encode(['success' => false, 'data' => [], 'error' => 'Conexión fallida']);
    exit;
}

$estado = isset($_GET['estado']) ? $_GET['estado'] : 'all';

try {
    $todos = listarConductores($conn, true);

    switch ($estado) {
        case 'activo':
            $conductores = array_filter($todos, fn($c) => (int)$c['activo'] === 1);
            break;
        case 'inactivo':
            $conductores = array_filter($todos, fn($c) => (int)$c['activo'] === 0);
            break;
        default:
            $conductores = $todos;
            break;
    }

    // Normalizar salida: asegurar que cada registro tenga las claves correctas
    $salida = array_map(function ($c) {
        return [
            'id' => $c['id'], // ⚠️ clave fundamental para los botones
            'nombres' => $c['nombres'] ?? '',
            'apellidos' => $c['apellidos'] ?? '',
            'dni' => $c['dni'] ?? '',
            'licencia_conducir' => $c['licencia_conducir'] ?? '',
            'telefono' => $c['telefono'] ?? '',
            'correo' => $c['correo'] ?? '',
            'direccion' => $c['direccion'] ?? '',
            'activo' => (int)($c['activo'] ?? 0),
            'foto' => !empty($c['foto']) ? '/uploads/conductores/' . basename($c['foto']) : null
        ];
    }, $conductores);

    echo json_encode([
        'success' => true,
        'data' => array_values($salida)
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'data' => [],
        'error' => $e->getMessage()
    ]);
}