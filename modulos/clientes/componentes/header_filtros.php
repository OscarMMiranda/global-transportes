<?php
/**
 * archivo: modulos/clientes/componentes/header_filtros.php
 */

if (!defined('GT_APP')) {
    define('GT_APP', true);
}
?>

<form method="get" class="filtros-modulo">
    <input type="hidden" name="action" value="list">

    <div class="row filtros-row">
        <div class="col-md-3 filtro-item">
            <label>Buscar</label>
            <input type="text" name="q" class="form-control" placeholder="Nombre, RUC, razón social">
        </div>

        <div class="col-md-2 filtro-item">
            <label>Estado</label>
            <select name="estado" class="form-control">
                <option value="">Todos</option>
                <option value="1">Activos</option>
                <option value="0">Inactivos</option>
            </select>
        </div>

        <div class="col-md-2 filtro-item">
            <label>&nbsp;</label>
            <button class="btn btn-primary btn-block btn-filtrar">Filtrar</button>
        </div>
    </div>
</form>
