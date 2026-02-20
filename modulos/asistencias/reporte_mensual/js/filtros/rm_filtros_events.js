// archivo: /modulos/asistencias/reporte_mensual/js/filtros/rm_filtros_events.js
// Este archivo contiene los eventos relacionados con los filtros del reporte mensual de asistencias

$(document).ready(function () {

    $('#btn_buscar').on('click', function () {
        rm_filtros_buscar();
    });

    $('#filtro_empresa').on('change', function () {
    rm_cargar_conductores();
});
});


