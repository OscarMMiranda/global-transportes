<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/componentes/tabs.php
?>

<ul class="nav nav-tabs mb-3" id="tabsTipoVehiculo" role="tablist">

    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tab-activos" data-bs-toggle="tab"
                data-bs-target="#panel-activos" type="button" role="tab">
            <i class="fa-solid fa-list-check me-1"></i> Activos
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-inactivos" data-bs-toggle="tab"
                data-bs-target="#panel-inactivos" type="button" role="tab">
            <i class="fa-solid fa-box-archive me-1"></i> Inactivos
        </button>
    </li>

    <li class="ms-auto">
        <button class="btn btn-success" id="btnNuevoTipoVehiculo">
            <i class="fa-solid fa-plus me-1"></i> Nuevo Tipo de Vehículo
        </button>
    </li>

</ul>

<div class="tab-content">

    <!-- TAB ACTIVOS -->
    <div class="tab-pane fade show active" id="panel-activos" role="tabpanel">
        <?php
        $tablaId = "tblActivosTipoVehiculo";
        include __DIR__ . '/tabla_tipo_vehiculo.php';
        ?>
    </div>

    <!-- TAB INACTIVOS -->
    <div class="tab-pane fade" id="panel-inactivos" role="tabpanel">
        <?php
        $tablaId = "tblInactivosTipoVehiculo";
        include __DIR__ . '/tabla_tipo_vehiculo.php';
        ?>
    </div>

</div>