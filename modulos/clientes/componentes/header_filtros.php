<?php
// archivo: modulos/clientes/componentes/header_filtros.php

if (!defined('GT_APP')) {
    define('GT_APP', true);
}
?>

<div class="row filtros-row mb-12">

    <!-- Filtro de texto -->
    <div class="col-md-4 filtro-item">
        <label>Buscar</label>
        <input type="text"
               id="filtroTextoClientes"
               class="form-control"
               placeholder="Nombre, RUC, correo">
    </div>

    <!-- Filtro de estado -->
    <div class="col-md-3 filtro-item">
        <label>Estado</label>
        <select id="filtroEstadoClientes" class="form-select">
            <option value="todos">Todos</option>
            <option value="activos">Activos</option>
            <option value="inactivos">Inactivos</option>
        </select>
    </div>

</div>
