// ============================================================
//  MÓDULO: rm_render_tabla.js
//  RESPONSABILIDAD: Renderizar la tabla del reporte mensual
// ============================================================

function rm_render_tabla(data) {

    let html = "";
    let totalMinutos = 0;

    if (!data || data.length === 0) {
        $("#tabla_reporte tbody").html(`
            <tr>
                <td colspan="10" class="text-center">No hay registros</td>
            </tr>
        `);
        $("#total_horas").text("00:00");
        return;
    }

    data.forEach(r => {

        let horasTrab = rm_calcular_horas_hhmm(r.hora_entrada, r.hora_salida);

        // Sumar al total
        let partes = horasTrab.split(":");
        totalMinutos += parseInt(partes[0]) * 60 + parseInt(partes[1]);

        html += `
            <tr>
                <td>${r.fecha}</td>
                <td>${r.conductor}</td>
                <td>${r.hora_entrada || "-"}</td>
                <td>${r.hora_salida || "-"}</td>
                <td>${horasTrab}</td>
                <td>${r.observacion || "-"}</td>
            </tr>
        `;
    });

    $("#tabla_reporte tbody").html(html);

    // Total mensual
    let totalFinal = rm_total_horas_hhmm(totalMinutos);
    $("#total_horas").text(totalFinal);
}
