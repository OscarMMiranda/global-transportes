// archivo: /modulos/asistencias/js/reporte_diario/tabla.js

$(document).ready(function () {

    console.log("tabla.js CARGADO");

    // Asegurar namespace global
    var RD = window.RD || {};
    window.RD = RD;

    /**
     * Carga el reporte diario según los filtros actuales.
     * Muestra un spinner mientras se obtiene la data.
     */
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

    /**
     * Renderiza la tabla del reporte diario.
     * Soporta vista compacta y vista completa.
     */
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

                    <td class="text-center">

                        <!-- BOTÓN EDITAR -->
                        <button class="btn btn-sm btn-primary me-1 btnEditarAsistencia"
                            data-id="${row.id}">
                            <i class="fa fa-pencil"></i>
                        </button>

                        <!-- BOTÓN HISTORIAL -->
                        <button class="btn btn-sm btn-info me-1 btnHistorialAsistencia"
                            data-id="${row.id}">
                            <i class="fa fa-history"></i>
                        </button>

                        <!-- BOTÓN ELIMINAR -->
                        <button class="btn btn-sm btn-danger btnEliminarAsistencia"
                            data-id="${row.id}">
                            <i class="fa fa-trash"></i>
                        </button>

                    </td>
                </tr>
                `;
            }

        });

        html += `</tbody></table></div>`;

        $("#contenedorReporteDiario").html(html);
    };

});
