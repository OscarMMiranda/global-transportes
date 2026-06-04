<?php
// archivo: /modulos/infracciones/ajax/obtener.php

require_once __DIR__ . '/../controllers/InfraccionesController.php';
require_once __DIR__ . '/../../../includes/config.php';

header('Content-Type: application/json');

$controller = new InfraccionesController($GLOBALS['db']);

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode(null);
    exit;
}

$inf = $controller->obtener($id);

if (!$inf) {
    echo json_encode(null);
    exit;
}

/* ============================================================
   OBTENER VALOR UIT VIGENTE
   ============================================================ */
$uit = $controller->model->getUitVigente();
$monto_base = 0;

if ($uit > 0 && isset($inf['porcentaje_uit'])) {
    $monto_base = ($inf['porcentaje_uit'] / 100) * $uit;
}

/* ============================================================
   OBTENER NOMBRE DE ENTIDAD EMISORA
   ============================================================ */
$entidad_nombre = "";

if (isset($inf['entidad_emisora_id'])) {

    $sql = "SELECT nombre FROM entidades_emisoras 
            WHERE id = " . intval($inf['entidad_emisora_id']) . " LIMIT 1";

    $res = $GLOBALS['db']->query($sql);

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $entidad_nombre = $row['nombre'];
    }
}

/* ============================================================
   ARMAR RESPUESTA COMPLETA
   ============================================================ */
$data = array(
    "id" => $inf['id'],
    "codigo" => $inf['codigo'],
    "descripcion" => $inf['descripcion'],
    "gravedad" => $inf['gravedad'],
    "puntos" => $inf['puntos'],

    // % UIT
    "porcentaje_uit" => $inf['porcentaje_uit'],

    // monto base calculado
    "monto_base" => round($monto_base, 2),

    "entidad_emisora_id" => $inf['entidad_emisora_id'],
    "entidad_nombre" => $entidad_nombre,

    // AUDITORÍA
    "creado_por" => isset($inf['creado_por']) ? $inf['creado_por'] : "",
    "fecha_creacion" => isset($inf['fecha_creacion']) ? $inf['fecha_creacion'] : "",
    "modificado_por" => isset($inf['modificado_por']) ? $inf['modificado_por'] : "",
    "fecha_modificacion" => isset($inf['fecha_modificacion']) ? $inf['fecha_modificacion'] : ""
);

echo json_encode($data);
exit;