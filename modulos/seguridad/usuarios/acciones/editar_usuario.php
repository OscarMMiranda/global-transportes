<?php
// archivo: /modulos/seguridad/usuarios/acciones/editar_usuario.php

require_once __DIR__ . '/../../../../includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';
requirePermiso("usuarios", "editar");

header('Content-Type: application/json; charset=utf-8');

$db = $GLOBALS['db'];

$id      = intval($_POST['usuario_id'] ?? 0);
$nombre  = trim($_POST['nombre'] ?? '');
$usuario = trim($_POST['usuario'] ?? '');
$correo  = trim($_POST['correo'] ?? '');
$rol_id  = intval($_POST['rol_id'] ?? 0);

if ($id <= 0 || $nombre === '' || $usuario === '' || $correo === '' || $rol_id === 0) {
    echo json_encode(["ok" => false, "msg" => "Complete todos los campos obligatorios"]);
    exit;
}

// Validar usuario duplicado (excepto el mismo)
$sql = "SELECT id FROM usuarios WHERE usuario = ? AND id != ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("si", $usuario, $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["ok" => false, "msg" => "El usuario ya existe"]);
    exit;
}

// Actualizar
$sql = "UPDATE usuarios SET nombre = ?, usuario = ?, correo = ?, rol_id = ? WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("sssii", $nombre, $usuario, $correo, $rol_id, $id);

if ($stmt->execute()) {
    echo json_encode(["ok" => true, "msg" => "Usuario actualizado correctamente"]);
} else {
    echo json_encode(["ok" => false, "msg" => "Error al actualizar"]);
}
