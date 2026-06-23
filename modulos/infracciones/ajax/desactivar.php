<?php
// archivo: /modulos/infracciones/ajax/desactivar.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// ============================================================
// VALIDAR SESIÓN
// ============================================================
if (!isset($_SESSION['usuario'])) {
    echo json_encode([
        'ok' => false,
        'msg' => 'Sesión expirada'
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
        'ok' => false,
        'msg' => 'ID no recibido'
    ]);
    exit;
}

$id = intval($_POST['id']);

// ============================================================
// DESACTIVAR (SOFT DELETE)
// ============================================================
$ok = $controller->desactivar($id);

echo json_encode([
    'ok' => $ok,
    'msg' => $ok ? 'Infracción desactivada correctamente' : 'No se pudo desactivar la infracción'
]);
exit;
