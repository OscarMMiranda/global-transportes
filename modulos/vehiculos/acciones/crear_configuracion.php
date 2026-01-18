<?php
// archivo: /modulos/vehiculos/acciones/crear_configuracion.php

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : "";
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : "";

if ($nombre === "") {
    echo json_encode(array("ok" => false, "msg" => "Debe ingresar un nombre."));
    exit;
}

// Validar duplicado por nombre
$stmt = $conn->prepare("SELECT id FROM configuracion_vehiculo WHERE nombre = ? LIMIT 1");
$stmt->bind_param("s", $nombre);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(array("ok" => false, "msg" => "Ya existe una configuración con ese nombre."));
    exit;
}
$stmt->close();

// Insertar
$sql = "INSERT INTO configuracion_vehiculo (nombre, descripcion) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $nombre, $descripcion);

if (!$stmt->execute()) {
    echo json_encode(array(
        "ok" => false,
        "msg" => "Error al guardar",
        "error_sql" => $stmt->error
    ));
    exit;
}

echo json_encode(array(
    "ok" => true,
    "msg" => "Configuración creada",
    "id"  => $stmt->insert_id,
    "nombre" => $nombre
));