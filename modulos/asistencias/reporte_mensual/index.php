<?php
// archivo: /modulos/asistencias/reporte_mensual/index.php
// Controlador principal del nuevo módulo Reporte Mensual

require_once __DIR__ . '/../../../includes/config.php';

// Si necesitas empresas para filtros:
require_once __DIR__ . '/../core/empresas.func.php';
$empresas = obtener_empresas($GLOBALS['db']);

include __DIR__ . '/componentes/header.php';


include __DIR__ . '/componentes/layout_open.php';
include __DIR__ . '/componentes/filtros.php';
include __DIR__ . '/componentes/totales.php';
include __DIR__ . '/componentes/tabla.php';
include __DIR__ . '/componentes/layout_close.php';
