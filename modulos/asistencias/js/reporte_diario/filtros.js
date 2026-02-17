// archivo: /modulos/asistencias/js/reporte_diario/filtros.js

$(document).ready(function () {

    console.log("filtros.js CARGADO");

    // Namespace global del módulo
    var RD = window.RD || {};
    window.RD = RD;

    /**
     * Obtiene los valores actuales de los filtros del Reporte Diario.
     * Devuelve un objeto con:
     *  - fecha
     *  - empresa
     */
    RD.obtenerFiltros = function () {
        return {
            fecha: $("#filtro_fecha").val(),
            empresa: $("#filtro_empresa").val()
        };
    };

});
