<?php
// ======================================================
//  	COMPONENTE		: 	filtros_panel_component.php
//  	MÓDULO			: 	Órdenes de Trabajo (OT)
//  	RESPONSABILIDAD	: 	Panel corporativo de filtros superiores
//  	GLOBAL 2026 — Arquitectura Limpia (Optimizado Visual)
// 		ESTADO 			: 	ACTIVO
// 		NO ELIMINAR
// ======================================================
?>

<section id="ot-filtros-panel"
         class="ot-filtros-panel card-corp mb-3"
         data-componente="filtros_panel"
         data-version="4.1">

    <!-- HEADER corporativo del panel -->
    <!-- <div class="ot-filtros-header py-2 px-3 bg-corp-primary text-white rounded-top">
        <h6 class="fw-bold mb-0">
            <i class="fa-solid fa-filter fa-fw me-1"></i>
            Filtros del Módulo
        </h6>
    </div> -->

    <!-- BODY corporativo -->
    <div class="ot-filtros-body px-3 pb-3 pt-3">

        <!-- Filtro por semana -->
        <?php include __DIR__ . '/filtro_semana_component.php'; ?>

        <!-- Filtro por estado (tabs operativos/logísticos) -->
        <?php include __DIR__ . '/../ot/tabs_estado_component.php'; ?>

    </div>

    <!-- DIVISOR corporativo -->
    <div class="card-corp-divider"></div>

</section>
