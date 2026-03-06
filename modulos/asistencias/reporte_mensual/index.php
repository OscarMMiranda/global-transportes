<?php
	// archivo: /modulos/asistencias/reporte_mensual/index.php
	// Controlador principal del nuevo módulo Reporte Mensual

	require_once __DIR__ . '/../../../includes/config.php';

	// Si necesitas empresas para filtros:
	require_once __DIR__ . '/../core/empresas.func.php';
	$empresas = obtener_empresas($GLOBALS['db']);

	include __DIR__ . '/componentes/header.php';
	include __DIR__ . '/componentes/layout_open.php';

	// include __DIR__ . '/componentes/btn_volver.php'; 

	include __DIR__ . '/componentes/filtros.php';
	include __DIR__ . '/componentes/totales.php';

?>


<!-- Vista TABLA -->
<div id="vista_tabla">
    <?php include __DIR__ . '/componentes/tabla.php'; ?>
</div>

<!-- Vista MATRIZ -->
<div id="vista_matriz" style="display:none;">
    <?php include __DIR__ . '/componentes/matriz.php'; ?>
</div>

<!-- LEYENDAS MATRIZ -->
<?php include __DIR__ . '/componentes/leyendas_matriz.php'; ?>

<?php
include __DIR__ . '/componentes/layout_close.php';



