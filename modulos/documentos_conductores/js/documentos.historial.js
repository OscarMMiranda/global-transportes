// archivo: documentos.historial.js
// Archivo desactivado. La lógica de historial está en documentos.carga.js

// ======================================================
//  FUNCIÓN: abrirHistorial() — VERSIÓN OFICIAL
// ======================================================

function abrirHistorial(tipoDocumentoId, descripcionDocumento) {

    console.log("ABRIR HISTORIAL EJECUTADO");

    const idConductor = $('#conductor_id').val();

    if (!idConductor || !tipoDocumentoId) {
        alert("No se pudo cargar el historial. Parámetros incompletos.");
        return;
    }

    $('#tituloHistorialDoc').text("Historial: " + descripcionDocumento);
    $('#modalHistorial').show();
    $('#modalHistorialContenido').html('<p class="text-muted">Cargando historial…</p>');

    $.ajax({
        url: '/modulos/documentos_conductores/acciones/listar_historial.php',
        method: 'POST',
        data: {
            conductor_id: idConductor,
            tipo_documento_id: tipoDocumentoId
        },
        dataType: 'json',

        success: function (res) {

            console.log("RESPUESTA HISTORIAL:", res);

            if (!res || res.ok !== true) {
                $('#modalHistorialContenido').html('<p class="text-danger">Error al cargar historial.</p>');
                return;
            }

            if (!res.historial || res.historial.length === 0) {
                $('#modalHistorialContenido').html('<p class="text-muted">Sin historial disponible.</p>');
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
            <button class="btn btn-sm btn-success me-1 btn-ver-doc" data-ruta="${h.ruta}">Ver</button>
            <a href="${h.ruta}" download class="btn btn-sm btn-secondary">Descargar</a>
        </td>
    </tr>
`;

            });

            html += `
                    </tbody>
                </table>
            `;

            $('#modalHistorialContenido').html(html);
        },

        error: function (xhr) {
            $('#modalHistorialContenido').html('<p class="text-danger">Error al cargar historial.</p>');
            console.error(xhr.responseText);
        }
    });
}

function cerrarHistorial() {
    $('#modalHistorial').hide();
}

$(document).on('click', '.btn-ver-doc', function () {
    const ruta = $(this).data('ruta');
    console.log("PREVIEW desde historial:", ruta);
    mostrarPreview(ruta);
});
