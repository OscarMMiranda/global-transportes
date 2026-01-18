<?php
// archivo: /modulos/seguridad/usuarios/acciones/listar_usuarios.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Cargar config.php usando ruta absoluta
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

require_once INCLUDES_PATH . '/permisos.php';
requirePermiso("usuarios", "ver");

header('Content-Type: application/json; charset=utf-8');

$db = $GLOBALS['db'];

$sql = "SELECT 
            u.id,
            u.nombre,
            u.usuario,
            r.nombre AS rol
        FROM usuarios u
        LEFT JOIN roles r ON r.id = u.rol
        ORDER BY u.nombre ASC";

$result = $db->query($sql);

if (!$result) {
    echo json_encode([
        "ok" => false,
        "msg" => "Error SQL: " . $db->error
    ]);
    exit;
}

$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}

echo json_encode([
    "ok" => true,
    "data" => $usuarios
]);