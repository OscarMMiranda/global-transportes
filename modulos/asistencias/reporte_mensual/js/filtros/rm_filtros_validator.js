// archivo  : /modulos/asistencias/reporte_mensual/js/filtros/rm_filtros_validator.js
// Funciones para validar los filtros del reporte mensual de asistencias

function rm_filtros_validar() {

    if (rm_filtros_state.mes === '') {
        alert('Seleccione un mes');
        return false;
    }

    if (rm_filtros_state.anio === '') {
        alert('Seleccione un año');
        return false;
    }

    return true;
}
