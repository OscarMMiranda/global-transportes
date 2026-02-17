//  archivo  : /modulos/asistencias/reporte_mensual/js/rm_main/rm_events_global.js
// Funciones para manejar eventos globales del reporte mensual de asistencias

$(document).ready(function () {

    rm_log('Módulo cargado: ' + RM_CONFIG.modulo);

    // Evento global: Enter ejecuta búsqueda
    $(document).on('keypress', function (e) {
        if (e.which === 13) {
            rm_filtros_buscar();
        }
    });

});
