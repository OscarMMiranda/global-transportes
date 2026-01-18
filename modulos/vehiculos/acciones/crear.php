<?php
// archivo: /modulos/vehiculos/acciones/crear.php

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// MISMA RUTA QUE ver.php
require_once __DIR__ . '/../../../includes/config.php';
require_once INCLUDES_PATH . '/funciones.php';

$conn = getConnection();
if (!$conn) {
    echo json_encode(array("ok" => false, "msg" => "Error de conexión"));
    exit;
}

// ---------------------------------------------------------
// CAPTURA DE DATOS (PHP 5.6)
// ---------------------------------------------------------
$placa            = isset($_POST['placa']) ? trim($_POST['placa']) : "";
$marca_id         = isset($_POST['marca_id']) ? intval($_POST['marca_id']) : 0;
$estado_id        = isset($_POST['estado_id']) ? intval($_POST['estado_id']) : 0;
$configuracion_id = isset($_POST['configuracion_id']) ? intval($_POST['configuracion_id']) : 0;

$tipo_id = (isset($_POST['tipo_id']) && $_POST['tipo_id'] !== "")
    ? intval($_POST['tipo_id'])
    : null;

$modelo = isset($_POST['modelo']) ? trim($_POST['modelo']) : "";

$anio = (isset($_POST['anio']) && $_POST['anio'] !== "")
    ? intval($_POST['anio'])
    : null;

$observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : "";

$empresa_id = isset($_POST['empresa_id']) ? intval($_POST['empresa_id']) : 0;
$usuario_id = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

// ---------------------------------------------------------
// VALIDACIÓN
// ---------------------------------------------------------
if ($placa === "" || $marca_id === 0 || $estado_id === 0 || $configuracion_id === 0 || $modelo === "") {
    echo json_encode(array("ok" => false, "msg" => "Complete todos los campos obligatorios."));
    exit;
}

if ($empresa_id === 0) {
    echo json_encode(array("ok" => false, "msg" => "Debe seleccionar una empresa."));
    exit;
}

// ---------------------------------------------------------
// VALIDAR PLACA DUPLICADA
// ---------------------------------------------------------
$stmt = $conn->prepare("SELECT id FROM vehiculos WHERE placa = ? LIMIT 1");
$stmt->bind_param("s", $placa);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(array("ok" => false, "msg" => "Ya existe un vehículo con esta placa."));
    exit;
}
$stmt->close();

// ---------------------------------------------------------
// INSERTAR VEHÍCULO
// ---------------------------------------------------------
$sql = "
INSERT INTO vehiculos 
(placa, marca_id, estado_id, configuracion_id, tipo_id, modelo, anio, empresa_id, observaciones, creado_por, activo)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(array("ok" => false, "msg" => "Error interno: " . $conn->error));
    exit;
}

// 10 parámetros
$types = "siiissssss";

// Ajustar tipos según NULL
// MODELO (índice 5) SIEMPRE string → NO SE TOCA
$types[6] = ($anio === null) ? "s" : "i";   // anio
$types[7] = "i";                            // empresa_id
$types[9] = ($usuario_id === null) ? "s" : "i"; // creado_por

$stmt->bind_param(
    $types,
    $placa,
    $marca_id,
    $estado_id,
    $configuracion_id,
    $tipo_id,
    $modelo,
    $anio,
    $empresa_id,
    $observaciones,
    $usuario_id
);

if (!$stmt->execute()) {
    echo json_encode(array(
        "ok" => false,
        "msg" => "Error al registrar",
        "error_sql" => $stmt->error
    ));
    exit;
}

echo json_encode(array(
    "ok" => true,
    "msg" => "Vehículo registrado correctamente",
    "id"  => $stmt->insert_id
));