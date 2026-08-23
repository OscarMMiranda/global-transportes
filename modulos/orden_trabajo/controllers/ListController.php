<?php
	// archivo: /modulos/orden_trabajo/controllers/ListController.php

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../models/OrdenModel.php';

$conn = getConnection();
$model = new OrdenModel($conn);

$semana = isset($_POST['semana']) ? trim($_POST['semana']) : "";
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : "";
$isAjax = (isset($_POST['ajax']) && $_POST['ajax'] == "1");

if ($semana !== "" && !preg_match('/^[0-9]{4}-W[0-9]{2}$/', $semana)) {
    $semana = "";
}

$ESTADO_ACTIVA    = 1;
$ESTADO_ANULADA   = 7;
$ESTADO_ELIMINADA = 8;

if ($isAjax) {

    switch ($estado) {
        case "ACTIVA":
            $rs = $model->obtenerActivasPorSemana($semana);
            break;

        case "ANULADA":
            $rs = $model->obtenerPorEstadoYSemana($ESTADO_ANULADA, $semana);
            break;

        case "ELIMINADA":
            $rs = $model->obtenerPorEstadoYSemana($ESTADO_ELIMINADA, $semana);
            break;

        default:
            $rs = array();
            break;
    }

    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode(["data" => $rs]);
    exit;
}

$data = [
    "semanas"    => $model->obtenerSemanas(),
    "semana_sel" => $semana
];

require __DIR__ . '/../views/list.php';
