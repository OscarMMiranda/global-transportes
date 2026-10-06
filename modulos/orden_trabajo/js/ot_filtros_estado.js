// ======================================================
//  JS: ot_filtros_estado.js
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Control de tabs de estado + recarga DT
//  GLOBAL 2026 — Arquitectura Limpia (Versión 4.6)
// ======================================================

// Estado seleccionado del módulo OT
var estadoOT = "TODAS";

// ======================================================
//  CAMBIAR ESTADO DESDE LOS TABS
// ======================================================
function cambiarEstadoOT(estado) {

    if (!estado) return;

    // Normalización corporativa
    var est = estado.toString().trim().toUpperCase();

    console.log("[OT] Cambio de estado:", est);

    // Guardar estado global
    estadoOT = est;

    // Quitar active de todos los tabs
    $('.ot-tab-estado').removeClass('active');

    // Activar el tab seleccionado (si existe)
    var tab = $('.ot-tab-estado[data-estado="' + est + '"]');

    if (tab.length > 0) {
        tab.addClass('active');
    } else {
        console.warn("[OT] Tab de estado no encontrado:", est);
    }

    // Recargar tabla sin perder paginación
    if (typeof tablaOT !== "undefined" && tablaOT !== null) {
        tablaOT.ajax.reload(null, false);
    }
}

// ======================================================
//  CAMBIAR ESTADO DESDE LOS FILTROS
// ======================================================
function cambiarEstadoOTFiltros(estado) {

    if (!estado) return;

    // Normalización corporativa
    var est = estado.toString().trim().toUpperCase();

    console.log("[OT] Cambio de estado:", est);

    // Guardar estado global
    estadoOT = est;

    // Recargar tabla sin perder paginación
    if (typeof tablaOT !== "undefined" && tablaOT !== null) {
        tablaOT.ajax.reload(null, false);
    }
}