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
// VALIDAR SI TIENE PAPELETAS ASOCIADAS
// ============================================================
if ($controller->tienePapeletas($id)) {
    echo json_encode([
        'ok' => false,
        'msg' => 'No se puede desactivar: tiene papeletas asociadas'
    ]);
    exit;
}

// ============================================================
// AUDITORÍA COMPLETA
// ============================================================
$usuario        = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'desconocido';
$usuario_id     = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 0;

$ip             = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
$host           = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;
$user_agent     = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;

$motivo         = isset($_POST['motivo']) ? trim($_POST['motivo']) : '';
$motivo         = $GLOBALS['db']->real_escape_string($motivo);

// ============================================================
// DESACTIVAR CON AUDITORÍA
// ============================================================
$ok = $controller->desactivarConAuditoria(
    $id,
    $usuario,
    $usuario_id,
    $ip,
    $host,
    $user_agent,
    $motivo
);

// ============================================================
// RESPUESTA
// ============================================================
echo json_encode([
    'ok' => $ok,
    'msg' => $ok ? 'Infracción desactivada correctamente' : 'No se pudo desactivar la infracción'
]);
exit;
