<?php
// archivo: /modulos/infracciones/ajax/obtener.php

require_once __DIR__ . '/../controllers/InfraccionesController.php';
require_once __DIR__ . '/../../../includes/config.php';

header('Content-Type: application/json');

// Controlador
$controller = new InfraccionesController($GLOBALS['db']);

// Validar ID
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode(["error" => "ID inválido"]);
    exit;
}

// Obtener infracción
$inf = $controller->obtener($id);

if (!$inf || !is_array($inf)) {
    echo json_encode(["error" => "No existe la infracción"]);
    exit;
}

/* ============================================================
   MONTO BASE (NO recalcular)
   ============================================================ */
$monto_base = isset($inf['monto_base'])
    ? floatval($inf['monto_base'])
    : 0;

/* ============================================================
   ENTIDAD EMISORA
   ============================================================ */
$entidad_nombre = "";
$entidad_id = isset($inf['entidad_emisora_id']) ? intval($inf['entidad_emisora_id']) : 0;

if ($entidad_id > 0) {

    $sql = "
        SELECT nombre
        FROM entidad_emisora
        WHERE id = {$entidad_id}
        LIMIT 1
    ";

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
    "id"                => intval($inf['id']),
    "codigo"            => $inf['codigo'],
    "descripcion"       => $inf['descripcion'],
    "gravedad"          => $inf['gravedad'],
    "puntos"            => intval($inf['puntos']),

    "porcentaje_uit"    => isset($inf['porcentaje_uit']) ? floatval($inf['porcentaje_uit']) : 0,
    "monto_base"        => number_format($monto_base, 2, '.', ''),

    "entidad_emisora_id" => $entidad_id,
    "entidad_nombre"      => $entidad_nombre,

    // AUDITORÍA
    "creado_por"        => isset($inf['creado_por']) ? $inf['creado_por'] : "No registrado",
    "fecha_creacion"    => isset($inf['fecha_creacion']) ? $inf['fecha_creacion'] : "No disponible",

    "modificado_por"    => isset($inf['modificado_por']) ? $inf['modificado_por'] : "No registrado",
    "fecha_modificacion"=> isset($inf['fecha_modificacion']) ? $inf['fecha_modificacion'] : "No disponible"
);

// Respuesta final
echo json_encode($data);
exit;
