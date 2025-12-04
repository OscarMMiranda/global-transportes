<?php
// archivo: /modulos/conductores/acciones/eliminar.php

require_once '../../../includes/config.php';
require_once '../controllers/conductores_controller.php';

header('Content-Type: application/json');

// Conexión segura
$conn = getConnection();
if (!$conn || !($conn instanceof mysqli)) {
    echo json_encode(['success' => false, 'error' => '❌ Error de conexión a la base de datos']);
    exit;
}

// Validar ID recibido
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'ID inválido']);
    exit;
}

// Ejecutar desactivación (soft delete)
try {
    $error = eliminarConductor($conn, $id);

    if ($error === '') {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $error]);
    }
} catch (Exception $e) {
    error_log('❌ Error en eliminar.php: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Error al desactivar conductor']);
}