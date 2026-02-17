// archivo  : /modulos/asistencias/reporte_mensual/js/filtros/rm_filtros_events.js
// Funciones para manejar los eventos de los filtros del reporte mensual de asistencias


$(document).ready(function () {

    // Cuando cambia la empresa → cargar conductores
    $('#filtro_empresa').on('change', function () {
        rm_cargar_conductores();
    });

    // Botón buscar
    $('#btn_buscar').on('click', function () {
        rm_filtros_buscar();
    });

    // Opcional: búsqueda automática
    $('#filtro_conductor, #filtro_mes, #filtro_anio').on('change', function () {
        // rm_filtros_buscar();
    });

});
