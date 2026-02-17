// archivo: /modulos/asistencias/js/reporte_diario/tabla.js
console.log("tabla.js CARGADO");

var RD = RD || {};

RD.cargarReporte = function () {

    let filtros = RD.obtenerFiltros();

    $("#contenedorReporteDiario").html(`
        <div class="text-center p-4">
            <div class="spinner-border"></div>
            <p>Cargando reporte...</p>
        </div>
    `);

    $.ajax({
        url: '/modulos/asistencias/acciones/obtener_reporte.php',
        type: 'GET',
        data: filtros,
        dataType: 'json',
        success: function (r) {

            if (!r.ok) {
                $("#contenedorReporteDiario").html(`
                    <div class="alert alert-danger">${r.error}</div>
                `);
                return;
            }

            if (!r.data || r.data.length === 0) {
                $("#contenedorReporteDiario").html(`
                    <div class="alert alert-info">No hay asistencias para los filtros seleccionados.</div>
                `);
                return;
            }

            RD.renderTabla(r.data);
        }
    });
};

RD.renderTabla = function (data) {

    let html = `
    <div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
    `;

    if (RD.vistaCompacta) {
        html += `
            <th>Conductor</th>
            <th>Tipo</th>
            <th>Entrada</th>
            <th>Salida</th>
        `;
    } else {
        html += `
    		<th>Conductor</th>
    		<th>Empresa</th>
    		<th>Tipo</th>
    		<th>Entrada</th>
    		<th>Salida</th>
    		<th>Observación</th>
    		<th>Acciones</th>
		`;


    }

    html += `</tr></thead><tbody>`;

    data.forEach(row => {

        if (RD.vistaCompacta) {
            html += `
                <tr>
                    <td>${row.conductor}</td>
                    <td>${row.tipo}</td>
                    <td>${row.hora_entrada || '-'}</td>
                    <td>${row.hora_salida || '-'}</td>
                </tr>
            `;
        } else {
            html += `
                <tr>
                    <td>${row.conductor}</td>
                    <td>${row.empresa}</td>
                    <td>${row.tipo}</td>
                    <td>${row.hora_entrada || '-'}</td>
                    <td>${row.hora_salida || '-'}</td>
                    <td>${row.observacion || '-'}</td>
                </tr>
            `;
        }

    });

    html += `</tbody></table></div>`;

    $("#contenedorReporteDiario").html(html);
};
