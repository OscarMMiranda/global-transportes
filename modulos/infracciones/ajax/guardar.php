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

$codigo = trim($_POST["codigo"]);

// Validar código único
if ($controller->existeCodigo($codigo)) {
    echo json_encode(array(
        "ok" => false,
        "msg" => "El código '$codigo' ya existe. Debe ser único."
    ));
    exit;
}

// Sanitización
$data = array(
    "codigo" => $codigo,
    "descripcion" => trim($_POST["descripcion"]),
    "gravedad" => trim($_POST["gravedad"]),
    "puntos" => intval($_POST["puntos"]),
    "porcentaje_uit" => floatval($_POST["porcentaje_uit"]),
    "entidad_emisora_id" => intval($_POST["entidad_emisora_id"])
);

// Guardar
$res = $controller->guardar($data);

echo json_encode(array("ok" => $res ? true : false));
exit;
