<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/componentes/tabla_tipo_vehiculo.php
// Requiere que $tablaId venga definido desde tabs.php

if (!isset($tablaId)) {
    $tablaId = 'tablaGenericaTipoVehiculo';
}
?>

<table id="<?= $tablaId ?>" class="table table-striped table-bordered table-hover w-100">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th style="width: 120px;">Acciones</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
