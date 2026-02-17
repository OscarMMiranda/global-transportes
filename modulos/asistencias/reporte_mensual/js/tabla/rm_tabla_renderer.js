// archivo: /modulos/asistencias/reporte_mensual/js/tabla/rm_tabla_renderer.js

function rm_render_tabla(data) {

    const tbody = $("#tabla_asistencias_body");
    tbody.empty();

    if (!data || data.length === 0) {
        tbody.append(`
            <tr>
                <td colspan="6" class="text-center text-muted">
                    No se encontraron datos
                </td>
            </tr>
        `);
        rm_totales_reset();
        return;
    }

    let totalHoras = 0;
    let totalAsistencias = 0;
    let totalFaltas = 0;

    data.forEach(item => {

        // Calcular horas trabajadas
        const horasTrab = rm_calcular_horas(item.hora_entrada, item.hora_salida);

        // Determinar estado
        const estado = rm_determinar_estado(item, horasTrab);

        // Construir fila
        const rowHtml = rm_build_row({
            fecha: rm_format_fecha(item.fecha),
            conductor: item.conductor,
            hora_entrada: rm_format_hora(item.hora_entrada),
            hora_salida: rm_format_hora(item.hora_salida),
            horas_trabajadas: horasTrab,
            estado: estado
        });

        tbody.append(rowHtml);

        // Totales
        totalHoras += parseFloat(horasTrab);

        if (estado === "ASISTENCIA") totalAsistencias++;
        if (estado === "SIN MARCAR" || estado === "INCOMPLETO") totalFaltas++;
    });

    rm_totales_update(totalAsistencias, totalFaltas, totalHoras);

}

