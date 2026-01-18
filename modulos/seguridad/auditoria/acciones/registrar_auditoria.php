<?php
// archivo: /modulos/seguridad/auditoria/acciones/registrar_auditoria.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

header('Content-Type: application/json; charset=utf-8');

session_start();

// -------------------------------
// Validar parámetros (PHP 5.6)
// -------------------------------
$modulo      = isset($_POST['modulo'])      ? $_POST['modulo']      : '';
$accion      = isset($_POST['accion'])      ? $_POST['accion']      : '';
$registro_id = isset($_POST['registro_id']) ? intval($_POST['registro_id']) : 0;
$descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';

$antes       = isset($_POST['antes'])       ? $_POST['antes']       : '';
$despues     = isset($_POST['despues'])     ? $_POST['despues']     : '';

// -------------------------------
// Validar usuario
// -------------------------------
$usuario_id     = isset($_SESSION['usuario_id'])     ? intval($_SESSION['usuario_id']) : 0;
$usuario_nombre = isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre']     : 'Desconocido';

if ($usuario_id <= 0) {
    echo json_encode(array("ok" => false, "msg" => "Usuario no autenticado"));
    exit;
}

// -------------------------------
// Validar campos mínimos
// -------------------------------
if ($modulo === '' || $accion === '' || $descripcion === '') {
    echo json_encode(array("ok" => false, "msg" => "Datos incompletos para auditoría"));
    exit;
}

// -------------------------------
// Validar conexión DB
// -------------------------------
if (!isset($GLOBALS['db'])) {
    echo json_encode(array("ok" => false, "msg" => "DB no inicializada"));
    exit;
}

$db = $GLOBALS['db'];

// -------------------------------
// Insertar auditoría
// -------------------------------
$sql = "INSERT INTO auditoria 
        (usuario_id, usuario_nombre, modulo, accion, registro_id, descripcion, valores_antes, valores_despues, ip, user_agent, fecha)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt = $db->prepare($sql);

if (!$stmt) {
    echo json_encode(array("ok" => false, "msg" => "Error en prepare(): " . $db->error));
    exit;
}

$ip         = isset($_SERVER['REMOTE_ADDR'])     ? $_SERVER['REMOTE_ADDR']     : '0.0.0.0';
$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'N/A';

// Convertir null a string vacío (PHP 5.6 no acepta null en bind_param)
$antes   = ($antes === null)   ? '' : $antes;
$despues = ($despues === null) ? '' : $despues;

$stmt->bind_param(
    "isssisssss",
    $usuario_id,
    $usuario_nombre,
    $modulo,
    $accion,
    $registro_id,
    $descripcion,
    $antes,
    $despues,
    $ip,
    $user_agent
);

if (!$stmt->execute()) {
    echo json_encode(array("ok" => false, "msg" => "Error al ejecutar: " . $stmt->error));
    exit;
}

echo json_encode(array("ok" => true));