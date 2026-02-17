// archivo  : /modulos/asistencias/reporte_mensual/js/tabla/rm_estado_icon.js
// Devuelve un ícono según el estado

function rm_estado_icon(estado) {
    switch (estado) {
        case "ASISTENCIA":
            return "✔️";
        case "INCOMPLETO":
            return "⚠️";
        case "SIN MARCAR":
            return "❌";
        case "FERIADO":
            return "🎉";
        default:
            return "";
    }
}
