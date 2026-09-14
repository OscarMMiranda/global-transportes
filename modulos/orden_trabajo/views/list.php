<?php
/**
 * archivo: /modulos/orden_trabajo/views/list.php
 *
 * Recibe: $data["semanas"], $data["semana_sel"]
 */

$semanas    = isset($data["semanas"]) ? $data["semanas"] : array();
$semana_sel = isset($data["semana_sel"]) ? $data["semana_sel"] : "";

$GLOBALS['__semanas']    = $semanas;
$GLOBALS['__semana_sel'] = $semana_sel;
?>

<!-- HEAD (CSS + LIBRERÍAS) -->
<?php include __DIR__ . '/../componentes/head.php'; ?>

<div class="container-fluid">

    <!-- HEADER CORPORATIVO -->
    <?php include __DIR__ . '/../componentes/header.php'; ?>

    <!-- BOTONES SUPERIORES -->
    <?php include __DIR__ . '/../componentes/botones_superiores.php'; ?>

    <!-- TABS DE ESTADO -->
    <?php include __DIR__ . '/../componentes/tabs_estado.php'; ?>

    <!-- TABLA CORPORATIVA -->
    <?php include __DIR__ . '/../componentes/tabla_ot.php'; ?>

</div>

<!-- MODALES CORPORATIVOS -->
<?php include __DIR__ . '/../modales/modal_ver.php'; ?>
<?php include __DIR__ . '/../modales/modal_crear.php'; ?>
<?php include __DIR__ . '/../modales/modal_editar.php'; ?>
<?php include __DIR__ . '/../modales/modal_anular.php'; ?>
<?php include __DIR__ . '/../modales/modal_eliminar.php'; ?>
<?php include __DIR__ . '/../modales/modal_importar.php'; ?>
<?php include __DIR__ . '/../modales/modal_editar_viaje.php'; ?>

<!-- FOOTER + SCRIPTS -->
<?php include __DIR__ . '/../componentes/footer.php'; ?>
<?php include __DIR__ . '/../componentes/scripts.php'; ?>
