<?php
// archivo: /modulos/orden_trabajo/controllers/ListController.php

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../models/OrdenModel.php';

$conn = getConnection();
$model = new OrdenModel($conn);

// ===============================
// 🔵 Parámetros recibidos (POST para AJAX)
// ===============================
$semana = isset($_POST['semana']) ? trim($_POST['semana']) : "";
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : "";
$isAjax = (isset($_POST['ajax']) && $_POST['ajax'] == "1");

// Validar formato corporativo YYYY-WXX
if ($semana !== "" && !preg_match('/^[0-9]{4}-W[0-9]{2}$/', $semana)) {
    $semana = "";
}

// ===============================
// 🔵 Estados corporativos
// ===============================
$ESTADO_ACTIVA    = 1;
$ESTADO_ANULADA   = 7;
$ESTADO_ELIMINADA = 8;

// ===============================
// 🔵 AJAX → devolver JSON corporativo
// ===============================
if ($isAjax) {

    switch ($estado) {

        case "ACTIVA":
            $data = $model->obtenerActivasPorSemana($semana);
            break;

        case "ANULADA":
            $data = $model->obtenerPorEstadoYSemana($ESTADO_ANULADA, $semana);
            break;

        case "ELIMINADA":
            $data = $model->obtenerPorEstadoYSemana($ESTADO_ELIMINADA, $semana);
            break;

        default:
            $data = array();
            break;
    }

    header("Content-Type: application/json; charset=UTF-8");

    echo json_encode(array(
        "estado" => "ok",
        "data"   => $data
    ));

    exit;
}

// ===============================
// 🔵 Vista HTML (carga inicial)
// ===============================
$data = array(
    "activas"    => $model->obtenerActivasPorSemana($semana),
    "anuladas"   => $model->obtenerPorEstadoYSemana($ESTADO_ANULADA, $semana),
    "eliminadas" => $model->obtenerPorEstadoYSemana($ESTADO_ELIMINADA, $semana),
    "semanas"    => $model->obtenerSemanas(),
    "semana_sel" => $semana
);

require __DIR__ . '/../views/list.php';
