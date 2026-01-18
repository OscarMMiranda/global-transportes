<?php
// archivo: /modulos/vehiculos/acciones/editar.php

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../includes/config.php';
require_once INCLUDES_PATH . '/funciones.php';

$conn = getConnection();
if (!$conn) {
    echo json_encode(array("ok" => false, "msg" => "Error de conexión"));
    exit;
}

// ---------------------------------------------------------
// ID
// ---------------------------------------------------------
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    echo json_encode(array("ok" => false, "msg" => "ID inválido"));
    exit;
}

// ---------------------------------------------------------
// CAPTURA DE DATOS
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
// VALIDAR PLACA DUPLICADA (EXCLUYENDO EL MISMO ID)
// ---------------------------------------------------------
$sqlDup = "SELECT id FROM vehiculos WHERE placa = ? AND id <> ? LIMIT 1";
$stmtDup = $conn->prepare($sqlDup);
$stmtDup->bind_param("si", $placa, $id);
$stmtDup->execute();
$stmtDup->store_result();

if ($stmtDup->num_rows > 0) {
    echo json_encode(array("ok" => false, "msg" => "Ya existe otro vehículo con esta placa."));
    exit;
}
$stmtDup->close();

// ---------------------------------------------------------
// UPDATE
// ---------------------------------------------------------
$sql = "
UPDATE vehiculos
SET placa = ?,
    marca_id = ?,
    estado_id = ?,
    configuracion_id = ?,
    tipo_id = ?,
    modelo = ?,
    anio = ?,
    empresa_id = ?,
    observaciones = ?,
    modificado_por = ?
WHERE id = ?
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(array("ok" => false, "msg" => "Error interno: " . $conn->error));
    exit;
}

// 11 parámetros
$types = "siiissssssi";

// Ajustar tipos según NULL
// MODELO (índice 5) SIEMPRE string → NO SE TOCA
$types[6] = ($anio === null) ? "s" : "i";   // anio
$types[7] = "i";                            // empresa_id
$types[9] = ($usuario_id === null) ? "s" : "i"; // modificado_por

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
    $usuario_id,
    $id
);

if (!$stmt->execute()) {
    echo json_encode(array(
        "ok" => false,
        "msg" => "Error al actualizar",
        "error_sql" => $stmt->error
    ));
    exit;
}

echo json_encode(array(
    "ok" => true,
    "msg" => "Vehículo actualizado correctamente"
));