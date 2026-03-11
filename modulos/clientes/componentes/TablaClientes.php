<?php
// archivo: /modulos/clientes/componentes/TablaClientes.php


if (!defined('GT_APP')) {
    define('GT_APP', true);
}
?>

<div class="contenedor-tabla-clientes">
    <table id="tablaClientes" class="table table-striped table-bordered table-sm tabla-clientes">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>RUC</th>
                <th>Dirección</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th class="col-acciones">Acciones</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
