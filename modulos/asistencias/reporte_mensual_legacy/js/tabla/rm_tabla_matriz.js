//  archivo: /modulos/asistencias/reporte_mensual/js/tabla/rm_tabla_matriz.js   
// Devuelve la tabla en formato matriz para el reporte mensual de asistencias

// data = {
//   dias: ["2025-07-01", "2025-07-02", ...],
//   conductores: [
//      {
//         id: 1,
//         nombre: "Juan Pérez",
//         asistencias: {
//             "2025-07-01": "ASISTIO",
//             "2025-07-02": "FALTO",
//             ...
//         }
//      }
//   ]
// }


function rm_render_tabla_matriz(data) {

    const dias = data.dias || [];
    const conductores = data.conductores || [];

    let html = `
        <div class="table-responsive">
        <table class="table table-bordered tabla-matriz">
    `;

    // ======================================================
    // HEADER
    // ======================================================
    html += `<thead><tr>`;
    html += `<th style="min-width:180px;">Conductor</th>`;

    dias.forEach(d => {
        const dia = d.substring(8); // "01", "02", etc.
        html += `<th class="text-center">${dia}</th>`;
    });

    html += `</tr></thead>`;

    // ======================================================
    // BODY
    // ======================================================
    html += `<tbody>`;

    conductores.forEach(c => {

        html += `<tr>`;
        html += `<td><strong>${c.nombre}</strong></td>`;

        dias.forEach(d => {

            const estado = (c.asistencias[d] || "").toUpperCase();

            let clase = "";

            switch (estado) {
                case "ASISTIO": clase = "estado-asistio"; break;
                case "FALTO": clase = "estado-falto"; break;
                case "FERIADO": clase = "estado-feriado"; break;
                case "VACACIONES": clase = "estado-vacaciones"; break;
                case "DOMINGO": clase = "estado-domingo"; break;
                case "DESC_MED": clase = "estado-desc-med"; break;
                default: clase = "estado-vacio"; break;
            }

            html += `<td class="text-center ${clase}">${estado}</td>`;
        });

        html += `</tr>`;
    });

    html += `</tbody></table></div>`;

    $('#tabla_reporte').html(html);
}
