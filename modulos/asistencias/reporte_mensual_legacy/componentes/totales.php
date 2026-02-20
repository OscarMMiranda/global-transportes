<?php
// archivo : /modulos/asistencias/reporte_mensual/componentes/totales.php
?>

<div class="panel panel-info">

    <?php include __DIR__ . '/totales/totales_header.php'; ?>
    <?php include __DIR__ . '/totales/totales_body.php'; ?>

    <?php
    $footer = __DIR__ . '/totales/totales_footer.php';
    if (file_exists($footer)) {
        include $footer;
    }
    ?>

</div>
