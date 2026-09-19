<?php
// ======================================================
//  CONTROLADOR: ListController.php
//  RESPONSABILIDAD: Listado de Órdenes de Trabajo (OT)
// ======================================================

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../models/OrdenModel.php';

$conn = getConnection();
$model = new OrdenModel($conn);

// ------------------------------------------------------
//  VALIDAR SESIÓN (corporativo)
// ------------------------------------------------------
if (!isset($_SESSION['usuario_id'])) {
    error_log("[ERP-OT] Acceso sin sesión: " . date('Y-m-d H:i:s'));
    header("Location: /login.php");
    exit;
}

// ------------------------------------------------------
//  ENTRADAS
// ------------------------------------------------------
$semana = isset($_POST['semana']) ? trim($_POST['semana']) : "";
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : "";
$isAjax = (isset($_POST['ajax']) && $_POST['ajax'] == "1");

// Validar formato de semana YYYY-Wxx
if ($semana !== "" && !preg_match('/^[0-9]{4}-W[0-9]{2}$/', $semana)) {
    $semana = "";
}

// Validar estado corporativo
$estadosValidos = array(
    "TODAS", "PENDIENTE", "EN_PROCESO", "COMPLETADA",
    "FACTURADA", "CANCELADA", "OBSERVADA", "ANULADA", "ELIMINADA"
);

if (!in_array($estado, $estadosValidos)) {
    $estado = "TODAS";
}

// ------------------------------------------------------
//  RESPUESTA AJAX (DataTables)
// ------------------------------------------------------
if ($isAjax) {

    try {

        $rs = $model->listarOT($estado, $semana);

        $response = array(
            "ok"   => true,
            "msg"  => "Listado obtenido correctamente",
            "data" => $rs
        );

        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($response);
        exit;

    } catch (Exception $e) {

        error_log("[ERP-OT] Error en listarOT: " . $e->getMessage());

        $response = array(
            "ok"   => false,
            "msg"  => "Error al obtener listado",
            "data" => array(),
            "error" => $e->getMessage()
        );

        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($response);
        exit;
    }
}

// ------------------------------------------------------
//  CARGA DE VISTA
// ------------------------------------------------------
$data = array(
    "semanas"    => $model->obtenerSemanas(),
    "semana_sel" => $semana
);

require __DIR__ . '/../views/list.php';
