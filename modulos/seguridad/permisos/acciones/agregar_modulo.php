<?php
// archivo: /modulos/seguridad/permisos/acciones/agregar_modulo.php

header('Content-Type: application/json');

require_once __DIR__ . '/../../../../includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';

requirePermiso("permisos", "crear");

if (!isset($_POST['nombre']) || trim($_POST['nombre']) === "") {
    echo json_encode(array("ok" => false, "msg" => "El nombre es obligatorio."));
    exit;
}

$nombre = trim($_POST['nombre']);

$conn = getConnection();

// Validar duplicado
$sqlCheck = "SELECT COUNT(*) FROM modulos WHERE nombre = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("s", $nombre);
$stmtCheck->execute();
$stmtCheck->bind_result($existe);
$stmtCheck->fetch();
$stmtCheck->close();

if ($existe > 0) {
    echo json_encode(array("ok" => false, "msg" => "El módulo ya existe."));
    exit;
}

// Insertar
$sql = "INSERT INTO modulos (nombre) VALUES (?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nombre);

if ($stmt->execute()) {
    echo json_encode(array("ok" => true, "msg" => "Módulo creado correctamente."));
} else {
    echo json_encode(array("ok" => false, "msg" => "Error al crear el módulo."));
}
exit;