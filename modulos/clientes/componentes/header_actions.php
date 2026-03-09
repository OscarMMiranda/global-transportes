<?php
/**
 * archivo: modulos/clientes/componentes/header_actions.php
 */

if (!defined('GT_APP')) {
    define('GT_APP', true);
}
?>

<div class="acciones-modulo">
    <a href="?action=form" class="btn btn-primary btn-sm btn-nuevo">
        <i class="fa fa-plus"></i> Nuevo Cliente
    </a>

    <a href="?action=trash" class="btn btn-secondary btn-sm btn-papelera">
        <i class="fa fa-trash"></i> Papelera
    </a>
</div>
