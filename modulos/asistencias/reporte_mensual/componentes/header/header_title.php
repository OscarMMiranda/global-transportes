<?php
// archivo: /modulos/asistencias/reporte_mensual/componentes/header/header_title.php

if (!isset($titulo)) $titulo = 'Reporte Mensual de Asistencias 2.0';
if (!isset($subtitulo)) $subtitulo = 'Control de Asistencias';
if (!isset($icono)) $icono = 'fa-calendar';
?>

<div class="rm-header-main">

    <div class="rm-header-left">
        <h4>
            <i class="fa <?php echo $icono; ?>"></i>
            <?php echo htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8'); ?>
        </h4>

        <?php if (!empty($subtitulo)) : ?>
            <small><?php echo htmlspecialchars($subtitulo, ENT_QUOTES, 'UTF-8'); ?></small>
        <?php endif; ?>
    </div>

    <div>
        <a href="/modulos/asistencias/vistas/index.php" class="btn btn-outline-primary rm-btn-volver">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
    </div>

</div>
