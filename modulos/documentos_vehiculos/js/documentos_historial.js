// ======================================================
//  HISTORIAL DE DOCUMENTOS DEL VEHÍCULO
// ======================================================

console.log("documentos_historial.js cargado");

// Abrir historial
$(document).on('click', '.btn-historial', function () {

    const tipo = $(this).data('tipo');
    const desc = $(this).data('desc');
    const vehiculoId = $('#vehiculo_id').val();

    if (!vehiculoId || !tipo) {
        alert("No se pudo cargar el historial. Parámetros incompletos.");
        return;
    }

    $('#tituloHistorialVehiculo').text("Historial: " + desc);
    $('#modalHistorialVehiculo').show();
    $('#contenidoHistorialVehiculo').html('<p class="text-muted">Cargando historial…</p>');

    $.ajax({
        url: '/modulos/documentos_vehiculos/acciones/listar_historial_vehiculo.php',
        method: 'POST',
        data: {
            vehiculo_id: vehiculoId,
            tipo_documento_id: tipo
        },
        dataType: 'json',

        success: function (res) {

            if (!res || res.ok !== true) {
                $('#contenidoHistorialVehiculo').html('<p class="text-danger">Error al cargar historial.</p>');
                return;
            }

            if (!res.historial || res.historial.length === 0) {
                $('#contenidoHistorialVehiculo').html('<p class="text-muted">Sin historial disponible.</p>');
                return;
            }

            let html = `
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Versión</th>
                            <th>Fecha subida</th>
                            <th>Fecha vencimiento</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            res.historial.forEach(h => {

                html += `
                    <tr>
                        <td>${h.version}</td>
                        <td>${h.fecha_subida}</td>
                        <td>${h.fecha_vencimiento}</td>
                        <td>${h.is_current == 1 ? 'Actual' : 'Anterior'}</td>
                        <td>
                            <button class="btn btn-sm btn-success me-1 btn-ver-doc"
                                    data-ruta="${h.ruta}">
                                Ver
                            </button>

                            <a href="${h.ruta}" download class="btn btn-sm btn-secondary">
                                Descargar
                            </a>
                        </td>
                    </tr>
                `;
            });

            html += `</tbody></table>`;

            $('#contenidoHistorialVehiculo').html(html);
        },

        error: function () {
            $('#contenidoHistorialVehiculo').html('<p class="text-danger">Error al cargar historial.</p>');
        }
    });
});

// Cerrar historial
function cerrarHistorialVehiculo() {
    $('#modalHistorialVehiculo').hide();
}
