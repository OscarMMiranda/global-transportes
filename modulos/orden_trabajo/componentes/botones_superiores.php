<?php
/**
 * archivo: /modulos/orden_trabajo/componentes/botones_superiores.php
 *
 * @var array  $semanas
 * @var string $semana_sel
 */
?>

<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

    <!-- 🔙 IZQUIERDA: VOLVER AL DASHBOARD -->
    <div class="mb-2">
        <button class="btn btn-corp-secondary btn-sm"
                onclick="window.location.href='/paneles/admin/controladores/dashboard_controlador.php'"
                title="Volver al Dashboard">
            <i class="fa-solid fa-arrow-left fa-sm fa-fw"></i> Dashboard
        </button>
    </div>

    <!-- 🔵 DERECHA: ACCIONES + FILTRO SEMANA -->
    <div class="d-flex align-items-center flex-wrap gap-2 mb-2">

        <!-- CREAR OT -->
        <button class="btn btn-corp-primary btn-sm"
                onclick="abrirModalCrearOT()"
                title="Crear nueva Orden de Trabajo">
            <i class="fa-solid fa-plus fa-sm fa-fw"></i> Nueva Orden
        </button>

        <!-- ANULAR -->
        <button class="btn btn-corp-warning btn-sm"
                onclick="abrirModalAnular()"
                title="Anular Orden de Trabajo">
            <i class="fa-solid fa-ban fa-sm fa-fw"></i> Anular
        </button>

        <!-- ELIMINAR -->
        <button class="btn btn-corp-danger btn-sm"
                onclick="abrirModalEliminar()"
                title="Eliminar Orden de Trabajo">
            <i class="fa-solid fa-trash fa-sm fa-fw"></i> Eliminar
        </button>

        <!-- IMPRIMIR -->
        <button class="btn btn-corp-dark btn-sm"
                onclick="imprimirListadoOT()"
                title="Imprimir listado">
            <i class="fa-solid fa-print fa-sm fa-fw"></i> Imprimir
        </button>

        <!-- EXPORTAR -->
        <button class="btn btn-corp-success btn-sm"
                onclick="exportarListadoOT()"
                title="Exportar listado a Excel">
            <i class="fa-solid fa-file-excel fa-sm fa-fw"></i> Exportar
        </button>

        <!-- IMPORTAR -->
        <button class="btn btn-corp-info btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#modalImportarOT"
                title="Importar órdenes desde archivo">
            <i class="fa-solid fa-file-import fa-sm fa-fw"></i> Importar OT
        </button>

        <!-- FILTRO SEMANA -->
        <?php 
            include_once __DIR__ . '/filtro_semana.php';
            renderFiltroSemana($semanas, $semana_sel);
        ?>

    </div>

</div>
