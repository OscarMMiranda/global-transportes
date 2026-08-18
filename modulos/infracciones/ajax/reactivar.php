<?php
// archivo: /modulos/infracciones/ajax/reactivar.php

session_start();

// SOLO ADMIN PUEDE RECUPERAR (rol = 1)
if (!isset($_SESSION['usuario']) || intval($_SESSION['rol']) !== 1) {
    echo json_encode([
        "ok"  => false,
        "msg" => "Acceso denegado. Solo un administrador puede recuperar infracciones."
    ]);
    exit;
}

// CONFIG GLOBAL
require_once __DIR__ . '/../../../includes/config.php';

// CONTROLADOR
require_once __DIR__ . '/../controllers/InfraccionesController.php';
$controller = new InfraccionesController($GLOBALS['db']);

// VALIDAR ID
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode([
        "ok"  => false,
        "msg" => "ID inválido"
    ]);
    exit;
}

// AUDITORÍA
$usuario        = $_SESSION['usuario'];
$usuario_id     = intval($_SESSION['usuario_id']);
$ip             = $_SERVER['REMOTE_ADDR'];
$host           = $_SERVER['HTTP_HOST'];
$user_agent     = $_SERVER['HTTP_USER_AGENT'];
$motivo         = isset($_POST['motivo']) ? trim($_POST['motivo']) : 'Recuperación de infracción';

// EJECUTAR RECUPERACIÓN CON AUDITORÍA
$res = $controller->reactivarConAuditoria(
    $id,
    $usuario,
    $usuario_id,
    $ip,
    $host,
    $user_agent,
    $motivo
);

// RESPUESTA
echo json_encode([
    "ok"  => $res ? true : false,
    "msg" => $res ? "Infracción reactivada correctamente" : "No se pudo reactivar"
]);
exit;
