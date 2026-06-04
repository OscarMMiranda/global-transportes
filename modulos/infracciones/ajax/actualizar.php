<?php
// archivo: /modulos/infracciones/ajax/actualizar.php

require_once __DIR__ . '/../controllers/InfraccionesController.php';
require_once __DIR__ . '/../../../includes/config.php';

header('Content-Type: application/json');

$controller = new InfraccionesController($GLOBALS['db']);

// Campos obligatorios
$required = array("id","codigo","descripcion","gravedad","puntos","porcentaje_uit","entidad_emisora_id");

foreach ($required as $r) {
    if (!isset($_POST[$r]) || trim($_POST[$r]) === "") {
        echo json_encode(array("ok" => false, "msg" => "Campo obligatorio: $r"));
        exit;
    }
}

$id = intval($_POST["id"]);
$codigo = trim($_POST["codigo"]);

// Validar código único excluyendo el ID actual
if ($controller->existeCodigo($codigo, $id)) {
    echo json_encode(array(
        "ok" => false,
        "msg" => "El código '$codigo' ya está registrado en otra infracción."
    ));
    exit;
}

// Sanitización
$data = array(
    "id" => $id,
    "codigo" => $codigo,
    "descripcion" => trim($_POST["descripcion"]),
    "gravedad" => trim($_POST["gravedad"]),
    "puntos" => intval($_POST["puntos"]),
    "porcentaje_uit" => floatval($_POST["porcentaje_uit"]),
    "entidad_emisora_id" => intval($_POST["entidad_emisora_id"])
);

// Actualizar
$res = $controller->actualizar($data);

echo json_encode(array("ok" => $res ? true : false));
exit;
