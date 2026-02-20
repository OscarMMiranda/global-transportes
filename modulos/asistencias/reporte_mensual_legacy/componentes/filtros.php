<?php
// archivo : /modulos/asistencias/reporte_mensual/componentes/filtros.php
?>

<div class="panel-body">
    <div class="row">

		<?php include __DIR__ . '/filtros/filtro_empresa.php'; ?>
        <?php include __DIR__ . '/filtros/filtro_conductor.php'; ?>
        <?php include __DIR__ . '/filtros/filtro_mes.php'; ?>
        <?php include __DIR__ . '/filtros/filtro_anio.php'; ?>
		<?php include __DIR__ . '/filtros/filtro_vista.php'; ?>
        <?php include __DIR__ . '/filtros/filtro_buscar.php'; ?>

		


    </div>
</div>
