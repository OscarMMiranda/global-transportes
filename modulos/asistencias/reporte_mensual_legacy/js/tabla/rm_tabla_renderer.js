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
        return;
    }

    data.forEach(item => {

        const horasTrab = rm_calcular_horas(item.hora_entrada, item.hora_salida);

        const estado = rm_determinar_estado(item, horasTrab);

        const rowHtml = rm_build_row({
            fecha: rm_format_fecha(item.fecha),
            conductor: item.conductor,
            hora_entrada: rm_format_hora(item.hora_entrada),
            hora_salida: rm_format_hora(item.hora_salida),
            horas_trabajadas: horasTrab,
            estado: estado
        });

        tbody.append(rowHtml);
    });
}


