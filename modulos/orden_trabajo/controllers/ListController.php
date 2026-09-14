<?php
// ======================================================
//  CONTROLADOR: ListController.php
//  RESPONSABILIDAD: Listado de Órdenes de Trabajo
// ======================================================

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../models/OrdenModel.php';

$conn = getConnection();
$model = new OrdenModel($conn);

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

// Validar estado
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

        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode(array("data" => $rs));
        exit;

    } catch (Exception $e) {

        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode(array(
            "data" => array(),
            "error" => "Error al obtener listado: " . $e->getMessage()
        ));
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
