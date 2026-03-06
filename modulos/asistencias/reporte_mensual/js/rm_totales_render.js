// archivo: modulos/asistencias/reporte_mensual/js/rm_totales_render.js

function rm_totales_render(t) {

    t = t || {};

    // -------------------------------
    // ASISTENCIAS Y FALTAS
    // -------------------------------
    $("#total_asistencias").val(t.total_asistencias || 0);
    $("#total_faltas").val(t.total_faltas || 0);

    // -------------------------------
    // TOTAL HORAS (decimal → HH:MM)
    // -------------------------------
    let totalHoras = parseFloat(t.total_horas || 0);
    let totalMin = Math.round(totalHoras * 60);

    let hh = Math.floor(totalMin / 60);
    let mm = totalMin % 60;

    const hhmm = hh.toString().padStart(2, "0") + ":" + mm.toString().padStart(2, "0");

    // Input del panel
    $("#total_horas").val(hhmm);

    // Footer de la tabla
    $("#total_horas_footer").text(hhmm);

    // -------------------------------
    // HORAS EXTRA
    // -------------------------------
    let extraHoras = parseFloat(t.total_horas_extra || 0);
    let extraMin = Math.round(extraHoras * 60);

    let eh = Math.floor(extraMin / 60);
    let em = extraMin % 60;

    const extraHHMM = eh.toString().padStart(2, "0") + ":" + em.toString().padStart(2, "0");

    $("#total_horas_extra").val(extraHHMM);
}

window.rm_totales_render = rm_totales_render;
