// archivo  : /modulos/asistencias/reporte_mensual/js/tabla/rm_tabla_formatters.js
// Funciones para formatear datos específicos en la tabla de asistencias del reporte mensual

function rm_format_fecha(fecha) {
    // Asume formato YYYY-MM-DD
    if (!fecha) return '';
    const partes = fecha.split('-');
    return `${partes[2]}/${partes[1]}/${partes[0]}`;
}

function rm_format_hora(hora) {
    return hora ? hora.substring(0, 5) : '';
}
