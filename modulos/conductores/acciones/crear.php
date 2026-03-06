<?php
// archivo: /modulos/conductores/acciones/crear.php

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../controllers/conductores_controller.php';

header('Content-Type: application/json; charset=utf-8');

// Conexión segura
$conn = getConnection();
if (!$conn || !($conn instanceof mysqli)) {
    echo json_encode(['success' => false, 'error' => '❌ Error de conexión a la base de datos']);
    exit;
}

// Forzar creación
$_POST['id'] = 0;

// Ejecutar guardado modular
$result = guardarConductor($conn, $_POST, $_FILES);

// Respuesta final
echo json_encode($result);
