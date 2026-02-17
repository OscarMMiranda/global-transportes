// archivo  : /modulos/asistencias/reporte_mensual/js/tabla/rm_determinar_estado.js
// Función para determinar el estado de asistencia según los datos de entrada

function rm_determinar_estado(item, horasTrab) {

    if (item.es_feriado == 1) return "FERIADO";

    if (item.hora_entrada === "00:00:00" && item.hora_salida === "00:00:00")
        return "SIN MARCAR";

    if (horasTrab === "0.00")
        return "INCOMPLETO";

    return "ASISTENCIA";
}
