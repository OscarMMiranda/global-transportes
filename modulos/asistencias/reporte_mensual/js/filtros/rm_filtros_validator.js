// archivo: /modulos/asistencias/reporte_mensual/js/filtros/rm_filtros_validator.js
// Este archivo contiene la función de validación para los filtros del reporte mensual de asistencias

function rm_filtros_validar() {

    if (!RM_FILTROS_STATE.mes) {
        alert('Seleccione un mes');
        return false;
    }

    if (!RM_FILTROS_STATE.anio) {
        alert('Seleccione un año');
        return false;
    }

    return true;
}
