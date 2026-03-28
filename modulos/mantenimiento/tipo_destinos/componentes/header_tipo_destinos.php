<?php
// archivo: /modulos/mantenimiento/tipo_destinos/componentes/header_tipo_destinos.php
// Componente: Header del módulo Tipo de Destinos
// Parámetros opcionales:
// $titulo, $icono, $rutaVolver

$titulo     = isset($titulo) ? $titulo : 'Mantenimiento: Tipo de destinos 2.0';
$icono      = isset($icono) ? $icono : 'fa-map-marker';
$rutaVolver = isset($rutaVolver) ? $rutaVolver : '../index.php';
?>

<div class="panel-heading clearfix">
    <h2 class="panel-title pull-left">
        <i class="fa <?= htmlspecialchars($icono) ?>"></i>
        <?= htmlspecialchars($titulo) ?>
    </h2>

    <div class="pull-right">
        <a href="<?= htmlspecialchars($rutaVolver) ?>" class="btn btn-default btn-sm">
            <i class="fa fa-arrow-left"></i> Volver
        </a>

        <button class="btn btn-success btn-sm" onclick="abrirFormulario()">
            <i class="fa fa-plus"></i> Agregar tipo
        </button>
    </div>
</div>
