<?php
// archivo: /modulos/clientes/ajax/activar_cliente.php
header('Content-Type: application/json');

require_once __DIR__ . '/../../../includes/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = getConnection();

if (!$conn) {
    echo json_encode(["status" => "error", "msg" => "Error de conexión"]);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode(["status" => "error", "msg" => "ID inválido"]);
    exit;
}

$usuario_id = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 0;
$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : "Desconocido";
$ip = $_SERVER['REMOTE_ADDR'];

// ===============================
// OBTENER DATOS ANTES
// ===============================
$sqlAntes = "SELECT * FROM clientes WHERE id = $id LIMIT 1";
$resultAntes = $conn->query($sqlAntes);

if (!$resultAntes || $resultAntes->num_rows === 0) {
    echo json_encode(["status" => "error", "msg" => "Cliente no encontrado"]);
    exit;
}

$datosAntes = $resultAntes->fetch_assoc();

// ===============================
// ACTIVAR CLIENTE
// ===============================
$sql = "
    UPDATE clientes 
    SET estado = 'Activo',
        modificado_por = $usuario_id,
        fecha_creacion = fecha_creacion
    WHERE id = $id
";

if (!$conn->query($sql)) {
    error_log("ERROR UPDATE: " . $conn->error);
    echo json_encode(["status" => "error", "msg" => "No se pudo activar el cliente"]);
    exit;
}

// ===============================
// OBTENER DATOS DESPUÉS
// ===============================
$sqlDespues = "SELECT * FROM clientes WHERE id = $id LIMIT 1";
$resultDespues = $conn->query($sqlDespues);

if (!$resultDespues || $resultDespues->num_rows === 0) {
    error_log("ERROR: datosDespues vacío");
    echo json_encode(["status" => "error", "msg" => "Error obteniendo datos actualizados"]);
    exit;
}

$datosDespues = $resultDespues->fetch_assoc();

// ===============================
// LIMPIAR UTF-8
// ===============================
function limpiar_utf8($texto) {
    return mb_convert_encoding($texto, 'UTF-8', 'UTF-8');
}

$jsonAntes = limpiar_utf8(json_encode($datosAntes, JSON_UNESCAPED_UNICODE));
$jsonDespues = limpiar_utf8(json_encode($datosDespues, JSON_UNESCAPED_UNICODE));

// ===============================
// REGISTRAR HISTORIAL
// ===============================
$sqlHist = "
    INSERT INTO clientes_historial
    (id_registro, modulo, accion, datos_antes_json, datos_despues_json, usuario, ip_origen)
    VALUES (?, 'clientes', 'activar', ?, ?, ?, ?)
";

$stmtHist = $conn->prepare($sqlHist);

if ($stmtHist) {

    $stmtHist->bind_param(
        "issss",
        $id,
        $jsonAntes,
        $jsonDespues,
        $usuario,
        $ip
    );

    if (!$stmtHist->execute()) {
        error_log("ERROR HISTORIAL: " . $stmtHist->error);
    }

    $stmtHist->close();
} else {
    error_log("ERROR PREPARE HISTORIAL: " . $conn->error);
}

// ===============================
// RESPUESTA FINAL
// ===============================
echo json_encode([
    "status" => "ok",
    "msg"    => "Cliente activado correctamente"
]);
exit;
