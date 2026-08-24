<?php
/**
 * archivo: /modulos/orden_trabajo/views/list.php
 *
 * Recibe: $data["semanas"], $data["semana_sel"]
 */

$semanas    = isset($data["semanas"]) ? $data["semanas"] : array();
$semana_sel = isset($data["semana_sel"]) ? $data["semana_sel"] : "";
?>

<!-- SOLO HTML — SIN HEAD, SIN HEADER, SIN SCRIPTS -->

<div class="container-fluid mt-4 px-3">

    <!-- FILTROS + BOTONES -->
    <?php include __DIR__ . '/../componentes/filtros_botones.php'; ?>

    <!-- TABS -->
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <button class="nav-link btn-estado active" data-estado="ACTIVA">Activas</button>
        </li>
        <li class="nav-item">
            <button class="nav-link btn-estado" data-estado="ANULADA">Anuladas</button>
        </li>
        <li class="nav-item">
            <button class="nav-link btn-estado" data-estado="ELIMINADA">Eliminadas</button>
        </li>
    </ul>

    <!-- TABLA -->
    <table id="tablaOT" class="table table-bordered table-striped w-100">
        <thead>
            <tr>
                <th>N°</th>
                <th>Número OT</th>
                <th>Fecha</th>
                <th>Semana</th>
                <th>Cliente</th>
                <th>OC Cliente</th>
                <th>Tipo OT</th>
                <th>Empresa</th>
                <th>N° Viajes</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
    </table>

</div>

<!-- MODAL VER -->
<?php include __DIR__ . '/../modales/modal_ver.php'; ?>
