// archivo: /modulos/asistencias/reporte_mensual/js/filtros/rm_filtros_state.js
// Este archivo define el estado de los filtros para el reporte mensual de asistencias

// Estado de filtros del reporte mensual
var RM_FILTROS_STATE = {
    empresa: '',
    conductor: '',
    mes: '',
    anio: '',
    vista: 'tabla'
};

function rm_filtros_leer() {
    RM_FILTROS_STATE.empresa   = $('#filtro_empresa').val();
    RM_FILTROS_STATE.conductor = $('#filtro_conductor').val();
    RM_FILTROS_STATE.mes       = $('#filtro_mes').val();
    RM_FILTROS_STATE.anio      = $('#filtro_anio').val();
    RM_FILTROS_STATE.vista     = $('#filtro_vista').val();
}
