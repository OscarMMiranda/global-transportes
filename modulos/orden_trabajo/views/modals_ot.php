<?php
// ======================================================
//  ARCHIVO: modulos/orden_trabajo/views/modals_ot.php
//  VISTA: modals_ot.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Cargar TODOS los modales del módulo OT
//  GLOBAL 2026 — Arquitectura Limpia (Optimizado)
// ======================================================

// NOTA IMPORTANTE:
// Este archivo centraliza todos los modales del módulo OT.
// Se carga DESPUÉS del overlay y ANTES de la vista principal.
// Esto garantiza que Bootstrap 5 y el overlay corporativo
// funcionen correctamente sin conflictos de z-index/backdrop.
// ======================================================
?>

<!-- ============================================
     MODAL: VER ORDEN DE TRABAJO
=============================================== -->
<?php include __DIR__ . '/../componentes/ot/modal_ver_ot_component.php'; ?>


<!-- ============================================
     MODAL: VER ORDEN DE VEHICULO (OV)
=============================================== -->
<?php include __DIR__ . '/../componentes/ov/modal_ver_ov_component.php'; ?>

<!-- ============================================
     MODAL: CREAR ORDEN DE TRABAJO
=============================================== -->
<?php include __DIR__ . '/../componentes/ot/modal_crear_ot_component.php'; ?>

<!-- ============================================
     MODAL: EDITAR ORDEN DE TRABAJO
=============================================== -->
<?php include __DIR__ . '/../componentes/ot/modal_editar_ot_component.php'; ?>

<!-- ============================================
     MODAL: ANULAR ORDEN DE TRABAJO
=============================================== -->
<?php include __DIR__ . '/../componentes/ot/modal_anular_ot_component.php'; ?>

<!-- ============================================
     MODAL: ELIMINAR ORDEN DE TRABAJO
=============================================== -->
<?php include __DIR__ . '/../componentes/ot/modal_eliminar_ot_component.php'; ?>

<!-- ============================================
     MODAL: IMPORTAR ÓRDENES DE TRABAJO
=============================================== -->
<?php include __DIR__ . '/../componentes/ot/modal_importar_ot_component.php'; ?>

<!-- ============================================
     MODAL: EDITAR VIAJE ASOCIADO
=============================================== -->
<?php include __DIR__ . '/../componentes/viajes/modal_editar_viaje_component.php'; ?>

<!-- ============================================
     MODAL: REGISTRAR VIAJE ASOCIADO
=============================================== -->
<?php include __DIR__ . '/../componentes/viajes/modal_registrar_viaje_component.php'; ?>

<!-- ============================================
     MODAL: ELIMINAR VIAJE ASOCIADO
=============================================== -->
<?php include __DIR__ . '/../componentes/viajes/modal_eliminar_viaje_component.php'; ?>	
