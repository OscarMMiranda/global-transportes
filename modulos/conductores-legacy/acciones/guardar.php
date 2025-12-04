<?php
// archivo: /modulos/conductores/acciones/guardar.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../includes/config.php';
require_once '../controllers/conductores_controller.php';

header('Content-Type: application/json');

$conn = getConnection();
if (!$conn || !($conn instanceof mysqli)) {
    echo json_encode(['success' => false, 'error' => '❌ Error de conexión a la base de datos']);
    exit;
}

try {
    $error = guardarConductor($conn, $_POST, $_FILES);

    if ($error === '') {
        $id = isset($_POST['id']) && intval($_POST['id']) > 0 ? intval($_POST['id']) : $conn->insert_id;
        $conductor = obtenerConductorPorId($conn, $id);

        echo json_encode([
            'success' => true,
            'data' => $conductor
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => $error
        ]);
    }
} catch (Exception $e) {
    error_log('❌ Error en guardar.php: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Error al guardar conductor']);
}