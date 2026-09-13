<?php
/**
 * archivo: /modulos/orden_trabajo/views/list.php
 *
 * Recibe: $data["semanas"], $data["semana_sel"]
 */

$semanas    = isset($data["semanas"]) ? $data["semanas"] : array();
$semana_sel = isset($data["semana_sel"]) ? $data["semana_sel"] : "";

// Pasar variables globales para botones_superiores.php
$GLOBALS['__semanas']    = $semanas;
$GLOBALS['__semana_sel'] = $semana_sel;
?>

<div class="container-fluid">

    <!-- BOTONES SUPERIORES + SELECT SEMANA -->
    <?php include __DIR__ . '/../componentes/botones_superiores.php'; ?>

    <button id="btnTestModal" class="btn btn-danger">Probar Modal</button>

    <!-- PESTAÑAS DE ESTADO (CORPORATIVO REAL) -->
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><button class="nav-link btn-estado active" data-estado="TODAS">Todas</button></li>
        <li class="nav-item"><button class="nav-link btn-estado" data-estado="PENDIENTE">Pendiente</button></li>
        <li class="nav-item"><button class="nav-link btn-estado" data-estado="EN_PROCESO">En proceso</button></li>
        <li class="nav-item"><button class="nav-link btn-estado" data-estado="COMPLETADA">Completada</button></li>
        <li class="nav-item"><button class="nav-link btn-estado" data-estado="FACTURADA">Facturada</button></li>
        <li class="nav-item"><button class="nav-link btn-estado" data-estado="CANCELADA">Cancelada</button></li>
        <li class="nav-item"><button class="nav-link btn-estado" data-estado="OBSERVADA">Observada</button></li>
        <li class="nav-item"><button class="nav-link btn-estado" data-estado="ANULADA">Anulada</button></li>
        <li class="nav-item"><button class="nav-link btn-estado" data-estado="ELIMINADA">Eliminada</button></li>
    </ul>

    <!-- TABLA CORPORATIVA -->
    <div class="table-responsive">
        <table id="tablaOT" class="table table-bordered table-striped w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Número OT</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>OC Cliente</th>
                    <th>Tipo OT</th>
                    <th>Empresa</th>
                    <th>Viajes</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

</div>

<!-- MODALES -->
<?php include __DIR__ . '/../modales/modal_ver.php'; ?>
<?php include __DIR__ . "/../modales/modal_crear.php"; ?>
<?php include __DIR__ . '/../modales/modal_editar.php'; ?>
<?php include __DIR__ . '/../modales/modal_anular.php'; ?>
<?php include __DIR__ . '/../modales/modal_eliminar.php'; ?>
<?php include __DIR__ . '/../modales/modal_importar.php'; ?>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/modulos/viajes/modales/modal_test.php"; ?>

<!-- MODAL NUEVO REGISTRAR VIAJE -->
<?php include __DIR__ . '/../modales/modal_registrar_viaje_nuevo.php'; ?>

<!-- ❌ NO CARGAR SCRIPTS AQUÍ -->
<!-- Los scripts ya se cargan en componentes/scripts.php -->
