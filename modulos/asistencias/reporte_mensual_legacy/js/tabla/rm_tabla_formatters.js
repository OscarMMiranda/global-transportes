// archivo  : /modulos/asistencias/reporte_mensual/js/tabla/rm_tabla_formatters.js
// Funciones para formatear datos específicos en la tabla de asistencias del reporte mensual

function rm_format_fecha(fecha) {
    if (!fecha) return '';
    const p = fecha.split('-');
    return `${p[2]}/${p[1]}/${p[0]}`;
}

function rm_format_hora(hora) {
    return hora ? hora.substring(0, 5) : '';
}
