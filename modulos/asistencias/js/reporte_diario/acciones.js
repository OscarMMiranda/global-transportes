// archivo: /modulos/asistencias/js/reporte_diario/acciones.js

$(document).ready(function () {

    console.log("acciones.js CARGADO");

    // Asegurar namespace global
    var RD = window.RD || {};
    window.RD = RD;

    /**
     * Exporta el reporte diario a Excel usando los filtros actuales.
     * Abre el archivo generado en una nueva pestaña.
     */
    RD.exportarExcel = function () {

        let filtros = RD.obtenerFiltros();

        let url =
            '/modulos/asistencias/acciones/exportar_reporte_excel.php'
            + '?fecha=' + encodeURIComponent(filtros.fecha)
            + '&empresa=' + encodeURIComponent(filtros.empresa);

        window.open(url, '_blank');
    };

});
