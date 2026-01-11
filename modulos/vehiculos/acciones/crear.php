<?php
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
$tipo_id          = ($_POST['tipo_id'] !== "") ? intval($_POST['tipo_id']) : null;
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
// INSERTAR VEHÍCULO
// ---------------------------------------------------------
$sql = "
INSERT INTO vehiculos 
(placa, marca_id, estado_id, configuracion_id, tipo_id, modelo, anio, empresa_id, observaciones, creado_por, activo)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["ok" => false, "msg" => "Error interno: " . $conn->error]);
    exit;
}

// Tipos dinámicos
$types = "siiisssss";
$types[5] = ($anio === null) ? "s" : "i";
$types[6] = ($empresa_id === null) ? "s" : "i";
$types[8] = ($usuario_id === null) ? "s" : "i";

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
    echo json_encode([
        "ok" => false,
        "msg" => "Error al registrar",
        "error_sql" => $stmt->error
    ]);
    exit;
}

echo json_encode([
    "ok" => true,
    "msg" => "Vehículo registrado correctamente",
    "id"  => $stmt->insert_id
]);