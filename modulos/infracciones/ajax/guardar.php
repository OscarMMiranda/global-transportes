<?php
// archivo: /modulos/infracciones/ajax/guardar.php

require_once __DIR__ . '/../controllers/InfraccionesController.php';
require_once __DIR__ . '/../../../includes/config.php';

header('Content-Type: application/json');

$controller = new InfraccionesController($GLOBALS['db']);

// Campos obligatorios
$required = array("codigo","descripcion","gravedad","puntos","porcentaje_uit","entidad_emisora_id");

foreach ($required as $r) {
    if (!isset($_POST[$r]) || trim($_POST[$r]) === "") {
        echo json_encode(array("ok" => false, "msg" => "Campo obligatorio: $r"));
        exit;
    }
}

// Sanitización
$codigo      = trim($_POST["codigo"]);
$descripcion = trim($_POST["descripcion"]);
$gravedad    = trim($_POST["gravedad"]);
$puntos      = intval($_POST["puntos"]);
$porcentaje  = floatval($_POST["porcentaje_uit"]);
$entidad_id  = intval($_POST["entidad_emisora_id"]);

// ============================================================
// VALIDAR CÓDIGO ÚNICO POR ENTIDAD
// ============================================================
if ($controller->existeCodigo($codigo, $entidad_id)) {
    echo json_encode(array(
        "ok" => false,
        "msg" => "El código '$codigo' ya está registrado como Activo para esta entidad."
    ));
    exit;
}

// ============================================================
// OBTENER UIT VIGENTE
// ============================================================
$uit = $controller->model->getUitVigente();

// ============================================================
// CALCULAR MONTO BASE SEGÚN REGLA
// ============================================================
// porcentaje_uit > 0 → calcular
// porcentaje_uit = 0 → monto_base = 0 (luego el usuario lo edita)
if ($porcentaje > 0 && $uit > 0) {
    $monto_base = ($porcentaje / 100) * $uit;
} else {
    $monto_base = 0.00;
}

// ============================================================
// ARMAR DATA PARA GUARDAR
// ============================================================
$data = array(
    "codigo" => $codigo,
    "descripcion" => $descripcion,
    "gravedad" => $gravedad,
    "puntos" => $puntos,
    "porcentaje_uit" => $porcentaje,
    "monto_base" => $monto_base,
    "entidad_emisora_id" => $entidad_id
);

// Guardar
$res = $controller->guardar($data);

echo json_encode(array("ok" => $res ? true : false));
exit;
