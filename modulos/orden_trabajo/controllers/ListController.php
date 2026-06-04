<?php
// archivo: /modulos/orden_trabajo/controllers/ListController.php

// ===============================
// 🔧 Conexión BD
// ===============================
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';
$conn = getConnection();

if (!$conn) {
    die("Error de conexión a la base de datos");
}

// ===============================
// 🔧 Modelo
// ===============================
require_once __DIR__ . '/../models/OrdenModel.php';

function cargarListado($conn)
{
    $model = new OrdenModel($conn);

    // ===============================
    // 🔵 Parámetros recibidos
    // ===============================
    $semana = isset($_GET['semana']) ? trim($_GET['semana']) : "";
    $estado = isset($_GET['estado']) ? trim($_GET['estado']) : "";
    $isAjax = (isset($_GET['ajax']) && $_GET['ajax'] == "1");

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
    // 🔵 AJAX → devolver solo JSON
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
                $data = [];
                break;
        }

        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($data);
        exit;
    }

    // ===============================
    // 🔵 Vista HTML (carga inicial)
    // ===============================
    $data = array(
        "activas"    => $model->obtenerActivasPorSemana($semana),
        "anuladas"   => $model->obtenerPorEstadoYSemana($ESTADO_ANULADA, $semana),
        "eliminadas" => $model->obtenerPorEstadoYSemana($ESTADO_ELIMINADA, $semana),
        "semanas"    => $model->obtenerSemanas(),   // PROFESIONAL: semanas desde fecha
        "semana_sel" => $semana
    );

    require __DIR__ . '/../views/list.php';
}

// ===============================
// 🔵 EJECUTAR CONTROLADOR
// ===============================
cargarListado($conn);
