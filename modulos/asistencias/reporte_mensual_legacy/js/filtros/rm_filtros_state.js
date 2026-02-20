// archivo  : /modulos/asistencias/reporte_mensual/js/filtros/rm_filtros_api.js
// Funciones para manejar los filtros del reporte mensual de asistencias

var rm_filtros_state = {
    empresa: '',
    conductor: '',
    mes: '',
    anio: '',
    vista: 'tabla'
};


function rm_filtros_leer() {

    rm_filtros_state.empresa   = $('#filtro_empresa').val();
    rm_filtros_state.conductor = $('#filtro_conductor').val();
    rm_filtros_state.mes       = $('#filtro_mes').val();
    rm_filtros_state.anio      = $('#filtro_anio').val();
    rm_filtros_state.vista     = $('#filtro_vista').val();
}

