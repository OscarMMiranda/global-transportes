// ============================================================
//  archivo : /modulos/asistencias/reporte_mensual/js/tabla/rm_totales.js
//  RESPONSABILIDAD: Calcular totales del mes y convertir minutos → HH:MM
// ============================================================

// ---------------------------------------------
// 1) Conversión de minutos a HH:MM
// ---------------------------------------------
function rm_total_horas_hhmm(totalMinutos) {

    if (!totalMinutos || totalMinutos <= 0) return "00:00";

    const h = Math.floor(totalMinutos / 60);
    const m = totalMinutos % 60;

    return h.toString().padStart(2, "0") + ":" + m.toString().padStart(2, "0");
}

window.rm_total_horas_hhmm = rm_total_horas_hhmm;

// ---------------------------------------------
// 2) Cálculo de totales desde response.data
// ---------------------------------------------
function rm_totales_calcular(data) {

    let totalAsistencias = 0;
    let totalFaltas = 0;
    let totalMinutos = 0;
    let totalMinutosExtra = 0;

    if (!data || data.length === 0) {
        return {
            total_registros: 0,
            total_faltas: 0,
            total_horas: "00:00",
            total_horas_extra: "00:00"
        };
    }

    data.forEach(r => {

        // Contar asistencias y faltas
        if (r.tipo === "Asistencia") totalAsistencias++;
        if (r.tipo === "Falta") totalFaltas++;

        // Calcular horas trabajadas
        let hhmm = rm_calcular_horas_hhmm(r.hora_entrada, r.hora_salida);

        if (hhmm !== "00:00") {
            let partes = hhmm.split(":");
            let minutos = parseInt(partes[0]) * 60 + parseInt(partes[1]);

            totalMinutos += minutos;

            // Horas extra (más de 8h por día)
            if (minutos > 480) {
                totalMinutosExtra += (minutos - 480);
            }
        }
    });

    return {
        total_registros: totalAsistencias,
        total_faltas: totalFaltas,
        total_horas: rm_total_horas_hhmm(totalMinutos),
        total_horas_extra: rm_total_horas_hhmm(totalMinutosExtra)
    };
}

window.rm_totales_calcular = rm_totales_calcular;
