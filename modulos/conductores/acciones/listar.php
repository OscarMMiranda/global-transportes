<?php
// archivo: /modulos/conductores/acciones/listar.php

header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Captura errores fatales al final del script
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null) {
        echo json_encode(array(
            'success' => false,
            'error'   => 'Error fatal: ' . $error['message']
        ));
    }
});

// Cargar config y controlador
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once __DIR__ . '/../controllers/conductores_controller.php';

// Obtener conexión
$conn = getConnection();
if (!$conn) {
    echo json_encode(array(
        'success' => false,
        'error'   => '❌ No se pudo conectar a la base de datos'
    ));
    exit;
}

// Leer parámetro estado
$estado = isset($_GET['estado']) ? $_GET['estado'] : 'activo';

// Validar valor permitido
if ($estado !== 'activo' && $estado !== 'inactivo') {
    $estado = 'activo';
}

try {
    // Llamar al controlador con el estado
    $rows = listarConductores($conn, $estado);

    // Validar que sea un array
    if (!is_array($rows)) {
        throw new Exception('El controlador no devolvió un array válido');
    }

    // Respuesta para DataTables
    echo json_encode(array(
        'success' => true,
        'data'    => $rows
    ));
} catch (Exception $e) {
    error_log("❌ listar.php: " . $e->getMessage());
    echo json_encode(array(
        'success' => false,
        'error'   => 'Error interno: ' . $e->getMessage()
    ));
}