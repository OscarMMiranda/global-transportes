<?php
// archivo: /modulos/vehiculos/acciones/crear.php

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";
$conn = getConnection();

if (!$conn) {
    echo json_encode(["ok" => false, "msg" => "Error de conexión"]);
    exit;
}

// ---------------------------------------------------------
// CAPTURA DE DATOS
// ---------------------------------------------------------
$placa            = trim($_POST['placa'] ?? "");
$marca_id         = intval($_POST['marca_id'] ?? 0);
$estado_id        = intval($_POST['estado_id'] ?? 0);
$configuracion_id = intval($_POST['configuracion_id'] ?? 0);
$modelo           = trim($_POST['modelo'] ?? "");
$anio             = ($_POST['anio'] !== "") ? intval($_POST['anio']) : null;
$observaciones    = trim($_POST['observaciones'] ?? "");
$empresa_id       = $_SESSION['empresa_id'] ?? null;
$usuario_id       = $_SESSION['usuario'] ?? null;

// Validación mínima
if ($placa === "" || $marca_id === 0 || $estado_id === 0 || $configuracion_id === 0 || $modelo === "") {
    echo json_encode(["ok" => false, "msg" => "Complete todos los campos obligatorios."]);
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
    echo json_encode(["ok" => false, "msg" => "Ya existe un vehículo con esta placa."]);
    exit;
}
$stmt->close();

// ---------------------------------------------------------
// INSERTAR VEHÍCULO (con NULL seguros)
// ---------------------------------------------------------
$sql = "
INSERT INTO vehiculos 
(placa, marca_id, estado_id, configuracion_id, modelo, anio, empresa_id, observaciones, creado_por, activo)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["ok" => false, "msg" => "Error interno: " . $conn->error]);
    exit;
}

// Convertir NULL a tipo correcto
$anio_sql     = $anio !== null ? $anio : null;
$empresa_sql  = $empresa_id !== null ? $empresa_id : null;
$usuario_sql  = $usuario_id !== null ? $usuario_id : null;

// Tipos dinámicos
$types = "siiisisss";

// Ajustar tipos cuando el valor es NULL
$types[5] = ($anio_sql === null)    ? "s" : "i";
$types[6] = ($empresa_sql === null) ? "s" : "i";
$types[8] = ($usuario_sql === null) ? "s" : "i";

$stmt->bind_param(
    $types,
    $placa,
    $marca_id,
    $estado_id,
    $configuracion_id,
    $modelo,
    $anio_sql,
    $empresa_sql,
    $observaciones,
    $usuario_sql
);

if ($stmt->execute()) {
    echo json_encode([
        "ok" => true,
        "msg" => "Vehículo registrado correctamente.",
        "id"  => $stmt->insert_id
    ]);
} else {
    echo json_encode([
        "ok" => false,
        "msg" => "Error al registrar: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();