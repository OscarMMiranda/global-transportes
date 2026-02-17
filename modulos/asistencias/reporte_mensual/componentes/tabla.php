<?php
// archivo : /modulos/asistencias/reporte_mensual/componentes/tabla.php
?>

<div class="panel-body">
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="tabla_asistencias">

            <?php include __DIR__ . '/tabla/tabla_head.php'; ?>
            <?php include __DIR__ . '/tabla/tabla_body.php'; ?>

            <?php
            // incluir footer solo si existe
            $footer = __DIR__ . '/tabla/tabla_footer.php';
            if (file_exists($footer)) {
                include $footer;
            }
            ?>

        </table>
    </div>
</div>
