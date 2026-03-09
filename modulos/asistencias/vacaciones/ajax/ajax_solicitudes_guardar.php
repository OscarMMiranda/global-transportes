<?php
header("Content-Type: application/json");

// Activar errores (PHP 5.6)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cargar config y conexión

require_once __DIR__ . "/../../../../includes/config.php";


// Obtener conexión MySQLi
$db = $GLOBALS['db'];

if (!$db || !($db instanceof mysqli)) {
    echo json_encode([
        "success" => false,
        "message" => "Error: conexión MySQLi no disponible."
    ]);
    exit;
}

// Recibir parámetros (PHP 5.6 no soporta ??)
$conductor  = isset($_POST['conductor'])  ? $_POST['conductor']  : '';
$inicio     = isset($_POST['inicio'])     ? $_POST['inicio']     : '';
$fin        = isset($_POST['fin'])        ? $_POST['fin']        : '';
$dias       = isset($_POST['dias'])       ? $_POST['dias']       : '';
$tipo       = isset($_POST['tipo'])       ? $_POST['tipo']       : '';
$comentario = isset($_POST['comentario']) ? $_POST['comentario'] : '';

if ($conductor == '' || $inicio == '' || $fin == '' || $dias == '') {
    echo json_encode([
        "success" => false,
        "message" => "Faltan datos obligatorios."
    ]);
    exit;
}

// SQL alineado a tu tabla real
$sql = "INSERT INTO vacaciones_solicitudes (
            conductor_id,
            fecha_inicio,
            fecha_fin,
            dias,
            tipo,
            estado,
            comentario,
            created_at
        ) VALUES (?, ?, ?, ?, ?, 'pendiente', ?, NOW())";

// Preparar
$stmt = $db->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Error en prepare(): " . $db->error
    ]);
    exit;
}

// PHP 5.6 requiere variables separadas
$cid  = intval($conductor);
$ini  = $inicio;
$fin2 = $fin;
$dd   = intval($dias);
$tp   = $tipo;
$com  = $comentario;

// bind_param (PHP 5.6)
$stmt->bind_param("ississ", $cid, $ini, $fin2, $dd, $tp, $com);

// Ejecutar
if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Solicitud registrada correctamente."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error SQL: " . $stmt->error
    ]);
}

$stmt->close();
