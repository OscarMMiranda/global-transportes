<?php
// archivo: /modulos/orden_trabajo/controllers/RestoreController.php

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
        "mensaje" => "No tienes permisos para restaurar órdenes"
    ));
    exit;
}

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
$ESTADO_ACTIVA = 1; // estado corporativo para activa

// ===============================
// 🔵 RESTAURAR ORDEN
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

$stmt->bind_param("ii", $ESTADO_ACTIVA, $id);

if ($stmt->execute()) {
    echo json_encode(array(
        "estado" => "ok",
        "mensaje" => "Orden restaurada correctamente"
    ));
    exit;
} else {
    echo json_encode(array(
        "estado" => "error",
        "mensaje" => "Error al restaurar: " . $stmt->error
    ));
    exit;
}
