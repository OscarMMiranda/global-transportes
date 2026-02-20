<?php
// archivo : /modulos/asistencias/reporte_mensual/acciones/obtener_reporte_mensual.php

header('Content-Type: application/json');

require_once '../../../../includes/config.php';

// Helpers del módulo AJAX (TABLA)
require_once __DIR__ . '/../ajax/helpers/validar_filtros.php';
require_once __DIR__ . '/../ajax/helpers/build_query.php';
require_once __DIR__ . '/../ajax/helpers/execute_query.php';
require_once __DIR__ . '/../ajax/helpers/json_response.php';

$cn = $GLOBALS['db'];

// Leer filtros
$mes       = $_POST['mes'] ?? '';
$anio      = $_POST['anio'] ?? '';
$conductor = $_POST['conductor'] ?? '';

// Validar
$valid = validar_filtros($mes, $anio);
if ($valid !== true) {
    json_error($valid);
}

// Construir SQL
list($sql, $types, $params) = build_query($conductor);

// Ejecutar
list($ok, $data) = ejecutar_consulta($cn, $sql, $types, $params, $mes, $anio);

if (!$ok) {
    json_error("Error al ejecutar consulta");
}

// Respuesta final
json_ok($data);
