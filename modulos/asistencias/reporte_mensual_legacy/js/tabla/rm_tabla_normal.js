//  archivo: /modulos/asistencias/reporte_mensual/js/tabla/rm_tabla_normal.js
// Render de la vista TABLA del reporte mensual de asistencias

function rm_render_tabla(data) {

    let html = `
        <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Conductor</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
    `;

    data.forEach(r => {
        html += `
            <tr>
                <td>${r.conductor}</td>
                <td>${r.fecha}</td>
                <td>${r.estado}</td>
            </tr>
        `;
    });

    html += `
            </tbody>
        </table>
        </div>
    `;

    $('#tabla_reporte').html(html);
}
