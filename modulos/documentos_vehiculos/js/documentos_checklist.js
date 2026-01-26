// archivo: /modulos/documentos_vehiculos/js/documentos_checklist.js

$(document).ready(function () {

    const vehiculoId = $('input[name="vehiculo_id"]').val();
    const tbody = $('#tablaChecklist tbody');

    // -------------------------------
    // 1. Cargar checklist
    // -------------------------------
    $.ajax({
        url: '/modulos/documentos_vehiculos/acciones/listar_documentos.php',
        type: 'POST',
        data: { vehiculo_id: vehiculoId },
        dataType: 'json',

        beforeSend: function () {
            tbody.html(`
                <tr>
                    <td colspan="4" class="text-center text-muted py-3">
                        Cargando documentos...
                    </td>
                </tr>
            `);
        },

        success: function (res) {

            if (!res || !res.documentos) {
                tbody.html(`
                    <tr>
                        <td colspan="4" class="text-center text-danger py-3">
                            No se pudo cargar el checklist.
                        </td>
                    </tr>
                `);
                return;
            }

            tbody.empty();

            res.documentos.forEach(function (doc) {

                let estado = '<span class="badge bg-secondary">No cargado</span>';

                if (doc.estado === 'OK') {
                    estado = '<span class="badge bg-success">OK</span>';
                } else if (doc.estado === 'Vencido') {
                    estado = '<span class="badge bg-danger">Vencido</span>';
                }

                const fecha = doc.fecha_vencimiento ? doc.fecha_vencimiento : '-';

                const fila = `
                    <tr>
                        <td>${estado}</td>
                        <td>${doc.descripcion}</td>
                        <td>${fecha}</td>
                        <td>
                            <input type="file" 
                                   name="archivo_${doc.tipo_documento_id}" 
                                   class="form-control form-control-sm"
                                   accept=".pdf,.jpg,.jpeg,.png">
                        </td>
                    </tr>
                `;

                tbody.append(fila);
            });
        },

        error: function () {
            tbody.html(`
                <tr>
                    <td colspan="4" class="text-center text-danger py-3">
                        Error al cargar los documentos.
                    </td>
                </tr>
            `);
        }
    });


    // -------------------------------
    // 2. Guardar tipo IMO
    // -------------------------------
    $('#guardarIMO').click(function () {

        const licenciaIMO = $('#licencia_imo').is(':checked') ? 1 : 0;

        $.ajax({
            url: '/modulos/documentos_vehiculos/acciones/guardar_tipo_imo.php',
            type: 'POST',
            data: {
                vehiculo_id: vehiculoId,
                licencia_imo: licenciaIMO
            },
            dataType: 'json',

            success: function (res) {
                alert(res.mensaje || 'Guardado.');
            },

            error: function () {
                alert('Error al guardar el tipo IMO.');
            }
        });
    });


    // -------------------------------
    // 3. Guardar documentos
    // -------------------------------
    $('#formDocumentos').submit(function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: '/modulos/documentos_vehiculos/acciones/guardar_documento.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',

            beforeSend: function () {
                $('#formDocumentos button[type="submit"]').prop('disabled', true);
            },

            success: function (res) {
                alert(res.mensaje || 'Documentos guardados.');
                location.reload();
            },

            error: function () {
                alert('Error al guardar los documentos.');
            },

            complete: function () {
                $('#formDocumentos button[type="submit"]').prop('disabled', false);
            }
        });
    });

});
