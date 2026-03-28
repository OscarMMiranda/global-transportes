<?php
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

$id = intval($_POST['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(["status" => "error", "msg" => "ID inválido"]);
    exit;
}

$usuario = $_SESSION['usuario'] ?? "Desconocido";
$ip = $_SERVER['REMOTE_ADDR'];

// ===============================
// OBTENER DATOS ANTES
// ===============================
$sqlAntes = "SELECT * FROM clientes WHERE id = $id LIMIT 1";
$resAntes = $conn->query($sqlAntes);

if (!$resAntes || $resAntes->num_rows === 0) {
    echo json_encode(["status" => "error", "msg" => "Cliente no encontrado"]);
    exit;
}

$datosAntes = $resAntes->fetch_assoc();

// ===============================
// DATOS NUEVOS
// ===============================
$nombre       = $_POST['nombre'] ?? $datosAntes['nombre'];
$tipo_cliente = $_POST['tipo_cliente'] ?? $datosAntes['tipo_cliente'];
$ruc          = $_POST['ruc'] ?? $datosAntes['ruc'];
$direccion    = $_POST['direccion'] ?? $datosAntes['direccion'];
$telefono     = $_POST['telefono'] ?? $datosAntes['telefono'];
$correo       = $_POST['correo'] ?? $datosAntes['correo'];

// ===============================
// ACTUALIZAR CLIENTE
// ===============================
$sql = "
    UPDATE clientes SET
        nombre = ?,
        tipo_cliente = ?,
        ruc = ?,
        direccion = ?,
        telefono = ?,
        correo = ?
    WHERE id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $nombre, $tipo_cliente, $ruc, $direccion, $telefono, $correo, $id);

if (!$stmt->execute()) {
    echo json_encode(["status" => "error", "msg" => "No se pudo editar"]);
    exit;
}

// ===============================
// OBTENER DATOS DESPUÉS
// ===============================
$sqlDespues = "SELECT * FROM clientes WHERE id = $id LIMIT 1";
$resDespues = $conn->query($sqlDespues);
$datosDespues = $resDespues->fetch_assoc();

// ===============================
// REGISTRAR HISTORIAL
// ===============================
$jsonAntes = json_encode($datosAntes, JSON_UNESCAPED_UNICODE);
$jsonDespues = json_encode($datosDespues, JSON_UNESCAPED_UNICODE);

$sqlHist = "
    INSERT INTO clientes_historial
    (id_registro, modulo, accion, datos_antes_json, datos_despues_json, usuario, ip_origen)
    VALUES (?, 'clientes', 'Editar', ?, ?, ?, ?)
";

$stmtHist = $conn->prepare($sqlHist);
$stmtHist->bind_param("issss", $id, $jsonAntes, $jsonDespues, $usuario, $ip);
$stmtHist->execute();

echo json_encode(["status" => "ok", "msg" => "Cliente editado correctamente"]);
exit;
