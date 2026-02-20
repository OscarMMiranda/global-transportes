// ARCHIVO: /modulos/asistencias/reporte_mensual/js/tabla/rm_vista_mensual.js
// ============================================================
//  MÓDULO: rm_vista_mensual.js
//  RESPONSABILIDAD: Renderizar vista tipo calendario
// ============================================================

function rm_render_vista_mensual(data, mes, anio) {

    let diasMes = new Date(anio, mes, 0).getDate();
    let html = "";

    // Crear matriz por día
    let mapa = {};
    for (let i = 1; i <= diasMes; i++) {
        mapa[i] = [];
    }

    // Agrupar registros por día
    data.forEach(r => {
        let dia = parseInt(r.fecha.split("-")[2]);
        mapa[dia].push(r);
    });

    // Renderizar
    for (let i = 1; i <= diasMes; i++) {

        html += `
            <div class="rm-dia">
                <div class="rm-dia-header">${i}</div>
                <div class="rm-dia-body">
        `;

        if (mapa[i].length === 0) {
            html += `<div class="rm-item rm-vacio">Sin registros</div>`;
        } else {
            mapa[i].forEach(r => {
                let horas = rm_calcular_horas_hhmm(r.hora_entrada, r.hora_salida);
                html += `
                    <div class="rm-item">
                        <div><b>${r.conductor}</b></div>
                        <div>${horas}</div>
                        <div>${r.observacion || "-"}</div>
                    </div>
                `;
            });
        }

        html += `
                </div>
            </div>
        `;
    }

    $("#vista_mensual").html(html);
}
