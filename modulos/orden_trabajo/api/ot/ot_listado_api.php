<?php
// ======================================================
//  ARCHIVO : /modulos/orden_trabajo/api/ot/ot_listado_api.php
//  API: ot_listado_api.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Proveer listado OT para DataTables
//  GLOBAL 2026 — Arquitectura Limpia (PHP 5.6 compatible)
// ======================================================

// ------------------------------
// Validación de sesión
// ------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['usuario_id'])) {
    echo json_encode(array(
        "ok"   => false,
        "msg"  => "Sesión no válida",
        "data" => array()
    ));
    exit;
}

// ------------------------------
// Cargar configuración global
// ------------------------------
require_once __DIR__ . '/../../../../includes/config.php';

// ------------------------------
// Conexión a la base de datos
// ------------------------------
$conn = getConnection();

if (!$conn) {

    error_log("[ERP-OT] Error de conexión en API listado OT: " . date('Y-m-d H:i:s'));

    echo json_encode(array(
        "ok"   => false,
        "msg"  => "Error de conexión con la base de datos",
        "data" => array()
    ));
    exit;
}

// ------------------------------
// Cargar modelo
// ------------------------------
require_once __DIR__ . '/../../models/OrdenTrabajoModel.php';
$model = new OrdenTrabajoModel($conn);

// ------------------------------
// Parámetros recibidos vía POST
// ------------------------------
$estado = isset($_POST['estado_ot']) ? trim($_POST['estado_ot']) : 'TODAS';
$semana = isset($_POST['semana'])    ? trim($_POST['semana'])    : '';

error_log("SEMANA RECIBIDA: [" . $semana . "]");
error_log("ESTADO RECIBIDO: [" . $estado . "]");


// Normalización corporativa
$estado = strtoupper($estado);

// ------------------------------
// Obtener data desde el modelo
// ------------------------------
$listado = $model->getListadoOT($semana, $estado);

// ------------------------------
// Respuesta para DataTables
// ------------------------------
echo json_encode(array(
    "ok"   => true,
    "msg"  => "Listado obtenido correctamente",
    "data" => $listado
));

exit;
