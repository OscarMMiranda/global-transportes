<?php
// ======================================================
//  	VISTA		:	listado_ot_view.php
//  	MÓDULO		:	Órdenes de Trabajo (OT)
//  	RESPONSABILIDAD: Vista principal del listado OT
//  	GLOBAL 2026 — Arquitectura Limpia
// 		ESTADO 		: 	ACTIVO
// 		NO ELIMINAR
// ======================================================

// El controlador debe enviar $data con:
// $data["semanas"]
// $data["semana_sel"]
// $data["estado_sel"]
// $data["ot_listado"]
?>

<div class="ot-listado-container">

<!-- BOTONES -->
<?php include __DIR__ . '/../componentes/shared/botones_superiores_component.php'; ?>

<!-- FILTROS -->
<?php include __DIR__ . '/../componentes/filtros/filtros_panel_component.php'; ?>

<!-- TABLA -->
<?php include __DIR__ . '/../componentes/tablas/tabla_ot_component.php'; ?>

</div>
