<?php
// archivo: /modulos/seguridad/usuarios/acciones/agregar_usuario.php

require_once __DIR__ . '/../../../../includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';
requirePermiso("usuarios", "agregar");

header('Content-Type: application/json; charset=utf-8');

$db = $GLOBALS['db'];

// Validar campos obligatorios
$nombre  = trim($_POST['nombre'] ?? '');
$usuario = trim($_POST['usuario'] ?? '');
$correo  = trim($_POST['correo'] ?? '');
$rol_id  = intval($_POST['rol_id'] ?? 0);

if ($nombre === '' || $usuario === '' || $correo === '' || $rol_id === 0) {
    echo json_encode(["ok" => false, "msg" => "Complete todos los campos obligatorios"]);
    exit;
}

// Validar usuario duplicado
$sql = "SELECT id FROM usuarios WHERE usuario = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["ok" => false, "msg" => "El usuario ya existe"]);
    exit;
}

// Insertar usuario
$sql = "INSERT INTO usuarios (nombre, usuario, correo, rol_id) VALUES (?, ?, ?, ?)";
$stmt = $db->prepare($sql);
$stmt->bind_param("sssi", $nombre, $usuario, $correo, $rol_id);

if ($stmt->execute()) {
    echo json_encode(["ok" => true, "msg" => "Usuario agregado correctamente"]);
} else {
    echo json_encode(["ok" => false, "msg" => "Error al guardar el usuario"]);
}