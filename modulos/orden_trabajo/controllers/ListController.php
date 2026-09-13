<?php
// archivo: /modulos/orden_trabajo/controllers/ListController.php

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../models/OrdenModel.php';

$conn = getConnection();
$model = new OrdenModel($conn);

$semana = isset($_POST['semana']) ? trim($_POST['semana']) : "";
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : "";
$isAjax = (isset($_POST['ajax']) && $_POST['ajax'] == "1");

// Validar formato de semana YYYY-Wxx
if ($semana !== "" && !preg_match('/^[0-9]{4}-W[0-9]{2}$/', $semana)) {
    $semana = "";
}

if ($isAjax) {

    try {

        // El modelo se encarga de resolver el estado
        $rs = $model->listarOT($estado, $semana);

        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode(["data" => $rs]);
        exit;

    } catch (Exception $e) {

        echo json_encode([
            "data" => [],
            "error" => "Error al obtener listado: " . $e->getMessage()
        ]);
        exit;
    }
}

// Carga de vista
$data = [
    "semanas"    => $model->obtenerSemanas(),
    "semana_sel" => $semana
];

require __DIR__ . '/../views/list.php';
