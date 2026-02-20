<?php
// archivo : /modulos/asistencias/reporte_mensual/componentes/header.php
?>

<div class="panel panel-primary">

    <?php include __DIR__ . '/header/header_title.php'; ?>

    <?php
    $acciones = __DIR__ . '/header/header_actions.php';
    if (file_exists($acciones)) {
        include $acciones;
    }
    ?>

</div>
