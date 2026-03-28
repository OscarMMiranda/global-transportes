<?php
// archivo: /modulos/asignaciones/componentes/filtros.php
?>

<div class="panel panel-default mb-3">
    <div class="panel-heading">
        <strong>Filtros</strong>
    </div>

    <div class="panel-body">

        <div class="row">

            <!-- FILTRO CONDUCTOR -->
            <div class="col-sm-3">
                <label for="filtroConductor">Conductor</label>
                <select id="filtroConductor"
                        name="filtro_conductor"
                        class="form-control"
                        data-role="filtro-conductor">
                    <option value="">Todos</option>
                </select>
            </div>

            <!-- FILTRO TRACTO -->
            <div class="col-sm-2">
                <label for="filtroTracto">Tracto</label>
                <select id="filtroTracto"
                        name="filtro_tracto"
                        class="form-control"
                        data-role="filtro-tracto">
                    <option value="">Todos</option>
                </select>
            </div>

            <!-- FILTRO CARRETA -->
            <div class="col-sm-2">
                <label for="filtroCarreta">Carreta</label>
                <select id="filtroCarreta"
                        name="filtro_carreta"
                        class="form-control"
                        data-role="filtro-carreta">
                    <option value="">Todos</option>
                </select>
            </div>

            <!-- FILTRO ESTADO -->
            <div class="col-sm-2">
                <label for="filtroEstado">Estado</label>
                <select id="filtroEstado"
                        name="filtro_estado"
                        class="form-control"
                        data-role="filtro-estado">
                    <option value="">Todos</option>
                    <option value="activo">Activo</option>
                    <option value="finalizado">Finalizado</option>
                </select>
            </div>

        </div>

    </div>
</div>
