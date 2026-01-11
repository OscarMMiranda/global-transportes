<?php
// archivo: /modulos/vehiculos/componentes/tabla_vehiculos.php
// Requiere que $tablaId venga definido desde tabs.php

// Validación defensiva
if (!isset($tablaId)) {
    $tablaId = 'tablaGenerica';
}
?>

<table id="<?= $tablaId ?>" class="table table-striped table-bordered table-hover w-100">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Placa</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Año</th>
            <th>Estado</th>
            <th style="width: 120px;">Acciones</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>