// archivo: /modulos/asistencias/js/reporte_diario/vistas.js

$(document).ready(function () {

    console.log("vistas.js CARGADO");

    // Asegurar namespace global
    var RD = window.RD || {};
    window.RD = RD;

    // Estado inicial de la vista
    RD.vistaCompacta = false;

    /**
     * Alterna entre vista compacta y vista completa.
     * Actualiza el texto del botón y recarga el reporte si corresponde.
     */
    RD.toggleVista = function () {

        RD.vistaCompacta = !RD.vistaCompacta;

        $("#btnToggleVista").html(
            RD.vistaCompacta
                ? '<i class="fa fa-list"></i> Vista Completa'
                : '<i class="fa fa-list"></i> Vista Compacta'
        );

        // Si existe la función cargarReporte, la ejecutamos
        if (typeof RD.cargarReporte === "function") {
            RD.cargarReporte();
        }
    };

});

