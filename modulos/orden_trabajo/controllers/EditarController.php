<?php
// archivo: /modulos/orden_trabajo/controllers/EditarController.php

header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';
$conn = getConnection();

// ===============================
// 🔵 VALIDAR MÉTODO
// ===============================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(array(
        "estado" => "error",
        "mensaje" => "Método no permitido"
    ));
    exit;
}

// ===============================
// 🔵 VALIDAR ID
// ===============================
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(array(
        "estado" => "error",
        "mensaje" => "ID inválido"
    ));
    exit;
}

$id = intval($_POST['id']);

// ===============================
// 🔵 VALIDAR CAMPOS OBLIGATORIOS
// ===============================
$campos = array('fecha', 'cliente_id', 'tipo_ot_id', 'empresa_id');

foreach ($campos as $campo) {
    if (!isset($_POST[$campo]) || trim($_POST[$campo]) === '') {
        echo json_encode(array(
            "estado" => "error",
            "mensaje" => "Falta el campo: " . $campo
        ));
        exit;
    }
}

// ===============================
// 🔵 SANITIZAR DATOS
// ===============================
$fecha      = mysqli_real_escape_string($conn, trim($_POST['fecha']));
$clienteID  = intval($_POST['cliente_id']);
$tipoOTID   = intval($_POST['tipo_ot_id']);
$empresaID  = intval($_POST['empresa_id']);
$ocCliente  = isset($_POST['oc_cliente']) ? mysqli_real_escape_string($conn, trim($_POST['oc_cliente'])) : '';
$descripcion = isset($_POST['descripcion']) ? mysqli_real_escape_string($conn, trim($_POST['descripcion'])) : '';

// ===============================
// 🔵 ACTUALIZAR OT
// ===============================
$sql = "
    UPDATE ordenes_trabajo
    SET 
        fecha = ?,
        cliente_id = ?,
        tipo_ot_id = ?,
        empresa_id = ?,
        oc_cliente = ?,
        descripcion = ?
    WHERE id = ?
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(array(
        "estado" => "error",
        "mensaje" => "Error preparando consulta: " . $conn->error
    ));
    exit;
}

$stmt->bind_param(
    "siisssi",
    $fecha,
    $clienteID,
    $tipoOTID,
    $empresaID,
    $ocCliente,
    $descripcion,
    $id
);

if ($stmt->execute()) {
    echo json_encode(array(
        "estado" => "ok",
        "mensaje" => "Orden actualizada correctamente"
    ));
    exit;
} else {
    echo json_encode(array(
        "estado" => "error",
        "mensaje" => "Error al actualizar: " . $stmt->error
    ));
    exit;
}
