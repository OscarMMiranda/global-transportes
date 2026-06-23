<?php
// archivo: /modulos/infracciones/ajax/ver.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// ============================================================
// VALIDAR SESIÓN
// ============================================================
if (!isset($_SESSION['usuario'])) {
    echo json_encode([
        'status' => 'error',
        'mensaje' => 'Sesión expirada'
    ]);
    exit;
}

// ============================================================
// CARGAR CONFIGURACIÓN Y CONTROLADOR
// ============================================================
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../controllers/InfraccionesController.php';

$controller = new InfraccionesController($GLOBALS['db']);

// ============================================================
// VALIDAR ID
// ============================================================
if (!isset($_POST['id']) || empty($_POST['id'])) {
    echo json_encode([
        'status' => 'error',
        'mensaje' => 'ID no recibido'
    ]);
    exit;
}

$id = intval($_POST['id']);

// ============================================================
// OBTENER DATOS
// ============================================================
$data = $controller->obtener($id);

if (!$data) {
    echo json_encode([
        'status' => 'error',
        'mensaje' => 'No se encontró la infracción'
    ]);
    exit;
}

// ============================================================
// RESPUESTA OK
// ============================================================
echo json_encode([
    'status' => 'ok',
    'data' => $data
]);
exit;
