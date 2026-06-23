<?php
// archivo: /modulos/infracciones/ajax/actualizar.php

require_once __DIR__ . '/../controllers/InfraccionesController.php';
require_once __DIR__ . '/../../../includes/config.php';

header('Content-Type: application/json');

// Instanciar controlador
$controller = new InfraccionesController($GLOBALS['db']);

// ============================================================
// VALIDAR CAMPOS OBLIGATORIOS
// ============================================================
$required = array(
    "id",
    "codigo",
    "descripcion",
    "gravedad",
    "puntos",
    "porcentaje_uit",
    "entidad_emisora_id"
);

foreach ($required as $r) {
    if (!isset($_POST[$r]) || trim($_POST[$r]) === "") {
        echo json_encode(array(
            "ok" => false,
            "msg" => "Campo obligatorio: $r"
        ));
        exit;
    }
}

// ============================================================
// SANITIZAR DATOS
// ============================================================
$id      = intval($_POST["id"]);
$codigo  = trim($_POST["codigo"]);
$desc    = trim($_POST["descripcion"]);
$grav    = trim($_POST["gravedad"]);
$puntos  = intval($_POST["puntos"]);
$porc    = floatval($_POST["porcentaje_uit"]);
$entidad = intval($_POST["entidad_emisora_id"]);

// ============================================================
// VALIDAR CÓDIGO ÚNICO EXCLUYENDO EL MISMO ID
// ============================================================
if ($controller->existeCodigo($codigo, $id)) {
    echo json_encode(array(
        "ok" => false,
        "msg" => "El código '$codigo' ya está registrado en otra infracción."
    ));
    exit;
}

// ============================================================
// ARMAR DATA PARA ACTUALIZAR
// ============================================================
$data = array(
    "id" => $id,
    "codigo" => $codigo,
    "descripcion" => $desc,
    "gravedad" => $grav,
    "puntos" => $puntos,
    "porcentaje_uit" => $porc,
    "entidad_emisora_id" => $entidad
);

// ============================================================
// ACTUALIZAR
// ============================================================
$res = $controller->actualizar($data);

// ============================================================
// RESPUESTA
// ============================================================
echo json_encode(array(
    "ok" => $res ? true : false
));
exit;
