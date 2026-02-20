//  archivo  : /modulos/asistencias/reporte_mensual/js/tabla/rm_tabla_styles.js
// Funciones para determinar las clases CSS y los íconos según el estado de asistencia en la tabla del reporte mensual

function rm_estado_class(estado) {
    switch (estado) {
        case 'ASISTENCIA': return 'text-success';
        case 'FALTA': return 'text-danger';
        case 'TARDANZA': return 'text-warning';
        default: return '';
    }
}

function rm_estado_icon(estado) {
    switch (estado) {
        case 'ASISTENCIA': return '<i class="fa fa-check-circle"></i>';
        case 'FALTA': return '<i class="fa fa-times-circle"></i>';
        case 'TARDANZA': return '<i class="fa fa-clock-o"></i>';
        default: return '';
    }
}
