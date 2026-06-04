<?php
// archivo: /modulos/orden_trabajo/controllers/VerController.php

header('Content-Type: application/json');

// ===============================
// 🔧 Conexión
// ===============================
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';
$conn = getConnection();

// ===============================
// 🔵 VALIDAR MÉTODO
// ===============================
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(array(
        "estado" => "error",
        "mensaje" => "Método no permitido"
    ));
    exit;
}

// ===============================
// 🔵 VALIDAR ID
// ===============================
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(array(
        "estado" => "error",
        "mensaje" => "ID inválido"
    ));
    exit;
}

$id = intval($_GET['id']);

// ===============================
// 🔧 Cargar modelo
// ===============================
require_once __DIR__ . '/../models/OrdenModel.php';
$model = new OrdenModel($conn);

// ===============================
// 🔵 OBTENER DATOS DE LA OT
// ===============================
try {

    $ot = $model->obtenerPorId($id);

    if (!$ot) {
        echo json_encode(array(
            "estado" => "error",
            "mensaje" => "La orden no existe"
        ));
        exit;
    }

    // ===============================
    // 🔵 RESPUESTA JSON CORPORATIVA
    // ===============================
    echo json_encode(array(
        "estado" => "ok",
        "data"   => $ot
    ));
    exit;

} catch (Exception $e) {

    error_log("Error en VerController.php: " . $e->getMessage());

    echo json_encode(array(
        "estado" => "error",
        "mensaje" => "Error al obtener datos"
    ));
    exit;
}
