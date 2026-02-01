//  archivo: /modulos/documentos_conductores/js/documentos.guardar.js 

function guardarDocumento() {

    var archivo = $('#archivoDocumento')[0].files[0];
    var fecha = $('#fechaVencimiento').val();

    if (!archivo) {
        notificar('error', 'Selecciona un archivo');
        return;
    }

    var formData = new FormData();
    formData.append('archivo', archivo);
    formData.append('fecha_vencimiento', fecha);
    formData.append('tipo_documento_id', tipoDocumentoActual);
    formData.append('conductor_id', idConductorActual);

    $('#btnGuardarDoc').prop('disabled', true);

    $.ajax({
        url: '/modulos/documentos_conductores/acciones/guardar_documento.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',

        success: function (resp) {
            console.log("respuesta guardar:", resp);

            if (resp.ok) {
                cerrarModalAdjuntar();
                cargarDocumentosConductor(idConductorActual);
                notificar('exito', 'Documento guardado correctamente');
            } else {
                notificar('error', resp.mensaje);
            }
        },

        error: function () {
            notificar('error', 'Error al guardar');
        },

        complete: function () {
            $('#btnGuardarDoc').prop('disabled', false);
        }
    });
}
