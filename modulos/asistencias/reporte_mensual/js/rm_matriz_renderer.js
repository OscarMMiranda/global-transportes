// ============================================================
// archivo : /modulos/asistencias/reporte_mensual/js/rm_matriz_renderer.js
// ============================================================

// ============================================================
// Función para calcular semana ISO (ISO-8601)
// ============================================================
function getISOWeek(fecha) {
    const temp = new Date(Date.UTC(fecha.getFullYear(), fecha.getMonth(), fecha.getDate()));
    const dayNum = temp.getUTCDay() || 7;
    temp.setUTCDate(temp.getUTCDate() + 4 - dayNum);
    const yearStart = new Date(Date.UTC(temp.getUTCFullYear(), 0, 1));
    return Math.ceil((((temp - yearStart) / 86400000) + 1) / 7);
}

// ============================================================
// RENDERIZADO DE VISTA MATRIZ
// ============================================================
function rm_render_vista_matriz(data, mes, anio) {

    // 1. Obtener cantidad de días del mes
    const diasMes = new Date(anio, mes, 0).getDate();

    // ============================================================
    // 2. Construir encabezado con semana ISO + inicial + número
    // ============================================================

    $("#matriz_body").html(""); // FIX

    let headSemanas = "<tr><th></th>";
    let headDias    = "<tr><th></th>";
    let headNums    = "<tr><th>Conductor</th>";

    let semanaAnterior = null;

    for (let d = 1; d <= diasMes; d++) {

        const fecha = new Date(anio, mes - 1, d);
        const diaSemana = fecha.getDay();
        const semanaISO = getISOWeek(fecha);

        const iniciales = ["D", "L", "M", "M", "J", "V", "S"];
        const inicial = iniciales[diaSemana];

        const claseDia =
            diaSemana === 0 ? "domingo" :
            diaSemana === 6 ? "sabado"  : "";

        const claseSemana = (semanaAnterior !== null && semanaISO !== semanaAnterior)
            ? "corte-semana"
            : "";

        semanaAnterior = semanaISO;

        // headSemanas += `<th class="${claseDia} ${claseSemana}">S${semanaISO}</th>`;
        // headDias    += `<th class="${claseDia} ${claseSemana}">${inicial}</th>`;
        // headNums    += `<th class="${claseDia} ${claseSemana}">${d}</th>`;
		headSemanas += `<th class="${claseDia}">S${semanaISO}</th>`;
		headDias    += `<th class="${claseDia}">${inicial}</th>`;
		headNums    += `<th class="${claseDia}">${d}</th>`;

    }

    headSemanas += "</tr>";
    headDias    += "</tr>";
    headNums    += "</tr>";

    $("#matriz_head").html(headSemanas + headDias + headNums);

    // ============================================================
    // 3. Agrupar por conductor
    // ============================================================
    const grupos = {};
    data.forEach(r => {
        if (!grupos[r.conductor_id]) {
            grupos[r.conductor_id] = {
                nombre: r.conductor,
                dias: {}
            };
        }
        const dia = parseInt(r.fecha.split("-")[2]);
        grupos[r.conductor_id].dias[dia] = r.tipo_codigo;
    });

    // ============================================================
    // 4. Construir cuerpo
    // ============================================================
    let body = "";

    Object.keys(grupos).forEach(cid => {
        const g = grupos[cid];

        body += `<tr><td>${g.nombre}</td>`;

        let semanaAnteriorFila = null;

        for (let d = 1; d <= diasMes; d++) {

            const fecha = new Date(anio, mes - 1, d);
            const diaSemana = fecha.getDay();
            const semanaISO = getISOWeek(fecha);

            const claseDia =
                diaSemana === 0 ? "domingo" :
                diaSemana === 6 ? "sabado"  : "";

            const claseSemana = (semanaAnteriorFila !== null && semanaISO !== semanaAnteriorFila)
                ? "corte-semana"
                : "";

            semanaAnteriorFila = semanaISO;

            const cod = g.dias[d] || "";
            const claseCodigo = cod ? `cod-${cod}` : "";

            body += `<td class="matriz-celda ${claseDia} ${claseSemana} ${claseCodigo}">${cod}</td>`;
            // body += `<td class="matriz-celda ${claseDia} ${claseSemana}">${cod}</td>`;
        }

        body += "</tr>";
    });

    $("#matriz_body").html(body);

    // ============================================================
    // 5. Mostrar leyendas SOLO en vista matriz
    // ============================================================
    $("#leyendas_matriz").show();
}

window.rm_render_vista_matriz = rm_render_vista_matriz;
