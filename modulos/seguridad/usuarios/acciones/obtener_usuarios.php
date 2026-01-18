<?php
// archivo: /modulos/seguridad/usuarios/acciones/obtener_usuario.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';
requirePermiso("usuarios", "ver");

header('Content-Type: application/json; charset=utf-8');

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(["ok" => false, "msg" => "ID inválido"]);
    exit;
}

$db = $GLOBALS['db'];

// Tu tabla NO tiene "correo"
$sql = "SELECT id, nombre, usuario, rol 
        FROM usuarios 
        WHERE id = ?";

$stmt = $db->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "ok" => false,
        "msg" => "Error en prepare(): " . $db->error
    ]);
    exit;
}

$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    echo json_encode([
        "ok" => false,
        "msg" => "Error al ejecutar: " . $stmt->error
    ]);
    exit;
}

// --- PHP 5.6 compatible: SIN get_result() ---
$stmt->bind_result($rid, $rnombre, $rusuario, $rrol);

if ($stmt->fetch()) {
    echo json_encode([
        "ok" => true,
        "data" => [
            "id" => $rid,
            "nombre" => $rnombre,
            "usuario" => $rusuario,
            "rol" => $rrol
        ]
    ]);
} else {
    echo json_encode(["ok" => false, "msg" => "Usuario no encontrado"]);
}