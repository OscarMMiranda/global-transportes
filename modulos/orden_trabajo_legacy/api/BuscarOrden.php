<?php
// archivo: /modulos/orden_trabajo/api/BuscarOrden.php

header('Content-Type: application/json');

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';
$conn = getConnection();

// ===============================
// 🔵 VALIDAR PERMISOS
// ===============================
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'ADMIN') {
    echo json_encode(array(
        "estado" => "error",
        "mensaje" => "Acceso denegado"
    ));
    exit;
}

// ===============================
// 🔵 VALIDAR PARÁMETRO
// ===============================
if (!isset($_GET['numero_ot']) || trim($_GET['numero_ot']) === '') {
    echo json_encode(array(
        "estado" => "error",
        "mensaje" => "Número OT no proporcionado"
    ));
    exit;
}

$numeroOT = mysqli_real_escape_string($conn, trim($_GET['numero_ot']));

// ===============================
// 🔵 CONSULTAR ORDEN
// ===============================
$sql = "
    SELECT 
        id,
        numero_ot,
        fecha,
        cliente_id,
        tipo_ot_id,
        empresa_id,
        estado_ot
    FROM ordenes_trabajo
    WHERE numero_ot = ?
    LIMIT 1
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(array(
        "estado" => "error",
        "mensaje" => "Error preparando consulta: " . $conn->error
    ));
    exit;
}

$stmt->bind_param("s", $numeroOT);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(array(
        "estado" => "error",
        "mensaje" => "Orden no encontrada"
    ));
    exit;
}

$orden = $res->fetch_assoc();

// ===============================
// 🔵 RESPUESTA JSON CORPORATIVA
// ===============================
echo json_encode(array(
    "estado" => "ok",
    "data"   => $orden
));
exit;
