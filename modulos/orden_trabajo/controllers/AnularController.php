<?php
// archivo: /modulos/orden_trabajo/controllers/AnularController.php

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
$ESTADO_ANULADA = 7;

// ===============================
// 🔵 ACTUALIZAR ESTADO
// ===============================
$sql = "UPDATE ordenes_trabajo SET estado_ot = ? WHERE id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(array(
        "estado" => "error",
        "mensaje" => "Error preparando consulta: " . $conn->error
    ));
    exit;
}

$stmt->bind_param("ii", $ESTADO_ANULADA, $id);

if ($stmt->execute()) {
    echo json_encode(array(
        "estado" => "ok",
        "mensaje" => "Orden anulada correctamente"
    ));
    exit;
} else {
    echo json_encode(array(
        "estado" => "error",
        "mensaje" => "Error al anular: " . $stmt->error
    ));
    exit;
}
