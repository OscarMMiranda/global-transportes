// archivo  : /modulos/asistencias/reporte_mensual/js/tabla/rm_estado_class.js
// Devuelve la clase CSS según el estado
// ASISTENCIA -> estado-asistencia

function rm_estado_class(estado) {
    switch (estado) {
        case "ASISTENCIA":
            return "estado-asistencia";
        case "INCOMPLETO":
            return "estado-incompleto";
        case "SIN MARCAR":
            return "estado-sin-marcar";
        case "FERIADO":
            return "estado-feriado";
        default:
            return "";
    }
}
