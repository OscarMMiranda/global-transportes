// ARCHIVO: /modulos/asistencias/reporte_mensual/js/tabla/rm_totales.js
// ============================================================
//  MÓDULO: rm_totales.js
//  RESPONSABILIDAD: Cálculo del total mensual en formato HH:MM
// ============================================================

function rm_total_horas_hhmm(totalMinutos) {

    if (!totalMinutos || totalMinutos <= 0) return "00:00";

    const h = Math.floor(totalMinutos / 60);
    const m = totalMinutos % 60;

    return h.toString().padStart(2, "0") + ":" + m.toString().padStart(2, "0");
}

window.rm_total_horas_hhmm = rm_total_horas_hhmm;
