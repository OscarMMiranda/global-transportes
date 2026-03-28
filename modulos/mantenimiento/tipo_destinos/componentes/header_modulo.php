<?php
// archivo: /modulos/mantenimiento/tipo_destinos/componentes/header_modulo.php
// Componente: Header del módulo de mantenimiento
// Parámetros esperados:
// $titulo (string)
// $icono (string)
// $rutaVolver (string)

$titulo     = isset($titulo) ? $titulo : 'Mantenimiento';
$icono      = isset($icono) ? $icono : 'fa-cog';
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
        <?php if (isset($botonesExtra)) echo $botonesExtra; ?>
    </div>
</div>
