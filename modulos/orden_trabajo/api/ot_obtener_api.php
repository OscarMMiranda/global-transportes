<?php
// ======================================================
//  API: ot_obtener_api.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Obtener datos de una OT por ID
//  GLOBAL 2026 — Arquitectura Limpia
// ======================================================

// ------------------------------
// Validación de sesión
// ------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(array(
        "success" => false,
        "message" => "Sesión no válida"
    ));
    exit;
}

// ------------------------------
// Cargar configuración global
// ------------------------------
require_once __DIR__ . '/../../../includes/config.php';

// ------------------------------
// Conexión a la base de datos
// ------------------------------
$conn = getConnection();
if (!$conn) {

    error_log("[ERP-OT] Error de conexión en API obtener OT: " . date('Y-m-d H:i:s'));

    echo json_encode(array(
        "success" => false,
        "message" => "Error de conexión al sistema"
    ));
    exit;
}

// ------------------------------
// Validación de método
// ------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(array(
        "success" => false,
        "message" => "Método no permitido"
    ));
    exit;
}

// ------------------------------
// Validación de parámetros
// ------------------------------
if (!isset($_GET['id']) || trim($_GET['id']) === '') {
    echo json_encode(array(
        "success" => false,
        "message" => "ID de OT no proporcionado"
    ));
    exit;
}

$id = trim($_GET['id']);

// ------------------------------
// Cargar modelo
// ------------------------------
require_once __DIR__ . '/../models/OrdenTrabajoModel.php';
$model = new OrdenTrabajoModel($conn);

// ------------------------------
// Obtener OT
// ------------------------------
$ot = $model->getOTById($id);

// ------------------------------
// Respuesta final
// ------------------------------
if ($ot) {
    echo json_encode(array(
        "success" => true,
        "data"    => $ot
    ));
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "OT no encontrada"
    ));
}

exit;
