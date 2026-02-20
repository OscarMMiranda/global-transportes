<?php
// archivo : /modulos/asistencias/reporte_mensual/componentes/footer.php
?>

<div class="panel-footer">

    <div class="row">

        <?php include __DIR__ . '/footer/footer_info.php'; ?>

        <?php
        $acciones = __DIR__ . '/footer/footer_actions.php';
        if (file_exists($acciones)) {
            include $acciones;
        }
        ?>

    </div>

    <?php include __DIR__ . '/footer/footer_brand.php'; ?>

</div>
