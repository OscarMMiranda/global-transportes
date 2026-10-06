<?php
// ======================================================
//  API: ot_crear_api.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Crear nueva OT
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
        "ok"      => false,
        "mensaje" => "Sesión no válida"
    ));
    exit;
}

// ------------------------------
// Cargar configuración global
// ------------------------------
// require_once __DIR__ . '/../../../includes/config.php';

require_once __DIR__ . '/../../../../includes/config.php';

// ------------------------------
// Conexión a la base de datos
// ------------------------------
$conn = getConnection();
if (!$conn) {

    error_log("[ERP-OT] Error de conexión en API crear OT: " . date('Y-m-d H:i:s'));

    echo json_encode(array(
        "ok"      => false,
        "mensaje" => "Error de conexión al sistema"
    ));
    exit;
}

// ------------------------------
// Validación de método
// ------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(array(
        "ok"      => false,
        "mensaje" => "Método no permitido"
    ));
    exit;
}

// ------------------------------
// Cargar modelo
// ------------------------------
// require_once __DIR__ . '/../models/OrdenTrabajoModel.php';

require_once __DIR__ . '/../../models/OrdenTrabajoModel.php';
$model = new OrdenTrabajoModel($conn);

// ------------------------------
// Ejecutar creación
// ------------------------------
$respuesta = $model->crearOT($_POST);

// ======================================================
// NORMALIZAR RESPUESTA PARA EL FRONTEND
// (tu JS usa r.ok, no r.success)
// ======================================================
$salida = array(
    "ok"      => isset($respuesta["success"]) ? $respuesta["success"] : false,
    "mensaje" => isset($respuesta["message"]) ? $respuesta["message"] : "",
    "data"    => isset($respuesta["data"]) ? $respuesta["data"] : null
);

// ------------------------------
// Respuesta final
// ------------------------------
echo json_encode($salida);
exit;
