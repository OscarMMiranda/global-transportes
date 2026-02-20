// archivo  : /modulos/asistencias/reporte_mensual/js/tabla/rm_tabla_row_builder.js
// Construye una fila usando los campos reales de asistencia_conductores

function rm_build_row(item) {

    const horasTrab = rm_calcular_horas(item.hora_entrada, item.hora_salida);

    const estado = rm_determinar_estado(item, horasTrab);

    return `
        <tr>
            <td>${item.fecha}</td>
            <td>${item.conductor}</td>
            <td>${item.hora_entrada}</td>
            <td>${item.hora_salida}</td>
            <td>${horasTrab}</td>
            <td class="${rm_estado_class(estado)}">
                ${rm_estado_icon(estado)} ${estado}
            </td>
        </tr>
    `;
}


/* ============================
   CÁLCULO DE HORAS TRABAJADAS
   ============================ */
function rm_calcular_horas(hEntrada, hSalida) {

    if (!hEntrada || !hSalida) return "0.00";
    if (hEntrada === "00:00:00" && hSalida === "00:00:00") return "0.00";

    const inicio = new Date(`2000-01-01T${hEntrada}`);
    const fin    = new Date(`2000-01-01T${hSalida}`);

    // Si la salida es 00:00:00 → no terminó turno
    if (hSalida === "00:00:00") return "0.00";

    const diffMs = fin - inicio;
    const horas = diffMs / 1000 / 60 / 60;

    return horas.toFixed(2);
}

/* ============================
   DETERMINAR ESTADO
   ============================ */
function rm_determinar_estado(item, horasTrab) {

    if (item.es_feriado == 1) return "FERIADO";

    if (item.hora_entrada === "00:00:00" && item.hora_salida === "00:00:00")
        return "SIN MARCAR";

    if (horasTrab === "0.00")
        return "INCOMPLETO";

    return "ASISTENCIA";
}
