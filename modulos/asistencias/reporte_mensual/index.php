<?php
	// archivo  : /modulos/asistencias/reporte_mensual/index.php
	// Controlador principal del módulo Reporte Mensual de Asistencias


require_once __DIR__ . '/../../../includes/config.php';

// Cargar funciones del core
require_once __DIR__ . '/../core/empresas.func.php';

// Obtener empresas para los filtros
$empresas = obtener_empresas($GLOBALS['db']);

include __DIR__ . '/componentes/layout_open.php';

include __DIR__ . '/componentes/header.php';
include __DIR__ . '/componentes/filtros.php';
include __DIR__ . '/componentes/totales.php';
include __DIR__ . '/componentes/tabla.php';
include __DIR__ . '/componentes/footer.php';

include __DIR__ . '/componentes/layout_close.php';
include __DIR__ . '/componentes/scripts.php';
