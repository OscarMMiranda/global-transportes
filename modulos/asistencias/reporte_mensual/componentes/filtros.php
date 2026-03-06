<?php
// archivo : /modulos/asistencias/reporte_mensual/componentes/filtros.php

$base = __DIR__ . '/filtros/';
?>

<div class="panel-body">

    <div class="row">

        <?php if (file_exists($base . 'filtro_empresa.php')) include $base . 'filtro_empresa.php'; ?>

        <?php if (file_exists($base . 'filtro_conductor.php')) include $base . 'filtro_conductor.php'; ?>

        <?php if (file_exists($base . 'filtro_mes.php')) include $base . 'filtro_mes.php'; ?>

        <?php if (file_exists($base . 'filtro_anio.php')) include $base . 'filtro_anio.php'; ?>

        <?php if (file_exists($base . 'filtro_vista.php')) include $base . 'filtro_vista.php'; ?>

        <?php if (file_exists($base . 'filtro_buscar.php')) include $base . 'filtro_buscar.php'; ?>

        <?php if (file_exists($base . 'filtro_pdf.php')) include $base . 'filtro_pdf.php'; ?>

    </div>

</div>