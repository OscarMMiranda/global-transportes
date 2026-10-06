<?php
// ======================================================
//  	COMPONENTE		: 	botones_superiores_component.php
//  	MÓDULO			: 	Órdenes de Trabajo (OT)
//  	RESPONSABILIDAD	: 	Botones superiores corporativos
//  	GLOBAL 2026 — Arquitectura Limpia (Optimizado Visual)
// 		ESTADO 			: 	ACTIVO
// 		NO ELIMINAR
// ======================================================
?>

<section id="ot-botones-superiores"
         class="d-flex justify-content-between align-items-center flex-wrap mb-2"
         data-componente="botones_superiores"
         data-version="3.2">

    <!-- IZQUIERDA: DASHBOARD -->
    <div class="mb-2">
        <a href="/paneles/admin/controladores/dashboard_controlador.php"
           class="btn btn-corp-secondary btn-sm px-3"
           title="Volver al Dashboard">
            <i class="fa-solid fa-arrow-left fa-sm fa-fw me-1"></i>
            Dashboard
        </a>
    </div>

    <!-- DERECHA: ACCIONES -->
    <div class="d-flex align-items-center flex-wrap gap-2 mb-2">

        <!-- ACCIÓN PRINCIPAL: NUEVA OT -->
        <button id="btn-ot-nueva"
                class="btn btn-corp-primary btn-sm px-3 fw-bold"
                title="Crear nueva Orden de Trabajo">
            <i class="fa-solid fa-plus fa-sm fa-fw me-1"></i>
            Nueva Orden
        </button>

        <!-- ACCIONES SECUNDARIAS -->
        <button class="btn btn-corp-warning btn-sm px-3"
                id="btnAnularOT"
                disabled
                title="Anular Orden de Trabajo">
            <i class="fa-solid fa-ban fa-sm fa-fw me-1"></i>
            Anular
        </button>

        <button class="btn btn-corp-danger btn-sm px-3"
                id="btnEliminarOT"
                disabled
                title="Eliminar Orden de Trabajo">
            <i class="fa-solid fa-trash fa-sm fa-fw me-1"></i>
            Eliminar
        </button>

        <!-- UTILITARIOS -->
        <button class="btn btn-corp-dark btn-sm px-3"
                id="btnImprimirOT"
                title="Imprimir listado">
            <i class="fa-solid fa-print fa-sm fa-fw me-1"></i>
            Imprimir
        </button>

        <button class="btn btn-corp-success btn-sm px-3"
                id="btnExportarOT"
                title="Exportar listado a Excel">
            <i class="fa-solid fa-file-excel fa-sm fa-fw me-1"></i>
            Exportar
        </button>

        <button class="btn btn-corp-info btn-sm px-3"
                data-bs-toggle="modal"
                data-bs-target="#modalImportarOT"
                title="Importar órdenes desde archivo">
            <i class="fa-solid fa-file-import fa-sm fa-fw me-1"></i>
            Importar OT
        </button>

    </div>

</section>
