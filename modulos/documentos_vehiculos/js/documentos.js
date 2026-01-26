// archivo: /modulos/documentos_vehiculos/js/documentos.js

var idVehiculoActual = 0;
var tipoDocumentoActual = null;
var descripcionDocumentoActual = '';

$(document).ready(function () {

    idVehiculoActual = $('#vehiculo_id').val();
    cargarDocumentosVehiculo(idVehiculoActual);

    $('#btnGuardarDoc').on('click', guardarDocumento);

    // Abrir modal para adjuntar o reemplazar
    $(document).on('click', '.boton-adjuntar', function (event) {
        tipoDocumentoActual = $(this).data('tipo');
        descripcionDocumentoActual = $(this).data('desc');

        var esReemplazo = $(this).hasClass('btn-warning');
        abrirModalAdjuntar(tipoDocumentoActual, descripcionDocumentoActual, esReemplazo);
    });

    // Abrir vista previa
    $(document).on('click', '.btn-ver-doc', function () {
        var ruta = $(this).data('ruta');
        mostrarPreview(ruta);
    });
});


// ======================================================
// 1. Cargar documentos del vehículo
// ======================================================
function cargarDocumentosVehiculo(idVehiculo) {

    var tabla = $('#tablaDocumentosVehiculo tbody');

    $.ajax({
        url: '/modulos/documentos_vehiculos/acciones/listar_documentos.php',
        method: 'POST',
        data: { vehiculo_id: idVehiculo },
        dataType: 'json',

        beforeSend: function () {
            tabla.html('<tr><td colspan="4" class="text-center text-muted">Cargando…</td></tr>');
        },

        success: function (res) {

            tabla.empty();

            if (!res || !res.documentos) {
                tabla.html('<tr><td colspan="4" class="text-center text-danger">Error al cargar</td></tr>');
                return;
            }

            $.each(res.documentos, function (i, doc) {

                var estado = '🔴 No cargado';
                var clase = 'estado-pendiente';

                if (doc.estado === 'OK') {
                    estado = '🟢 OK';
                    clase = 'estado-ok';
                } else if (doc.estado === 'Vencido') {
                    estado = '🔴 Vencido';
                    clase = 'estado-vencido';
                }

                var fecha = doc.fecha_vencimiento ? doc.fecha_vencimiento : '—';

                // Botones dinámicos
                var botones = '';

                if (doc.ruta) {
                    botones +=
                        '<button class="btn btn-sm btn-success me-1 btn-ver-doc" data-ruta="' + doc.ruta + '">Ver</button>' +
                        '<a href="' + doc.ruta + '" download class="btn btn-sm btn-secondary me-1">Descargar</a>' +
                        '<button type="button" class="boton-adjuntar btn btn-sm btn-warning" ' +
                        'data-tipo="' + doc.tipo_documento_id + '" ' +
                        'data-desc="' + escapeHtml(doc.descripcion) + '">' +
                        'Reemplazar</button>';
                } else {
                    botones +=
                        '<button type="button" class="boton-adjuntar btn btn-sm btn-primary" ' +
                        'data-tipo="' + doc.tipo_documento_id + '" ' +
                        'data-desc="' + escapeHtml(doc.descripcion) + '">' +
                        '📎 Adjuntar</button>';
                }

                tabla.append(
                    '<tr>' +
                    '<td class="' + clase + '">' + estado + '</td>' +
                    '<td>' + escapeHtml(doc.descripcion) + '</td>' +
                    '<td>' + fecha + '</td>' +
                    '<td>' + botones + '</td>' +
                    '</tr>'
                );
            });
        },

        error: function () {
            tabla.html('<tr><td colspan="4" class="text-center text-danger">Error al cargar</td></tr>');
        }
    });
}


// ======================================================
// 2. Modal para adjuntar documento
// ======================================================
function abrirModalAdjuntar(tipoId, descripcion, esReemplazo) {

    tipoDocumentoActual = tipoId;
    descripcionDocumentoActual = descripcion;

    $('#tituloModalDoc').text((esReemplazo ? 'Reemplazar: ' : 'Adjuntar: ') + descripcion);

    $('#archivoDocumento').val('');
    $('#fechaVencimiento').val('');
    $('#modalAdjuntarDocumento').show();
}

function cerrarModalAdjuntar() {
    $('#modalAdjuntarDocumento').hide();
}


// ======================================================
// 3. Guardar documento
// ======================================================
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
    formData.append('vehiculo_id', idVehiculoActual);

    $('#btnGuardarDoc').prop('disabled', true);

    $.ajax({
        url: '/modulos/documentos_vehiculos/acciones/guardar_documento.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',

        success: function (resp) {
            if (resp.ok) {
                cerrarModalAdjuntar();
                cargarDocumentosVehiculo(idVehiculoActual);
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


// ======================================================
// 4. Vista previa de documentos (PDF / Imagen)
// ======================================================
function mostrarPreview(ruta) {

    var ext = ruta.split('.').pop().toLowerCase();
    var html = '';

    if (ext === 'pdf') {
        html = '<embed src="' + ruta + '" type="application/pdf" width="100%" height="100%">';
    } else if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
        html = '<img src="' + ruta + '" style="max-width:100%; max-height:100%;">';
    } else {
        html = '<p class="text-center mt-5">No se puede previsualizar este tipo de archivo.</p>';
    }

    $('#previewContenido').html(html);
    $('#modalPreviewDocumento').show();
}

function cerrarPreview() {
    $('#modalPreviewDocumento').hide();
    $('#previewContenido').html('');
}


// ======================================================
// 5. Sanitizar texto
// ======================================================
function escapeHtml(text) {
    return text
        .replace(/&/g, "&amp;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
}


// ======================================================
// 6. Notificaciones ERP
// ======================================================
function notificar(tipo, mensaje) {
    var cont = $('#notificacionesERP');

    var div = $('<div class="notificacion ' + tipo + '">' + mensaje + '</div>');
    cont.append(div);

    setTimeout(function () {
        div.addClass('mostrar');
    }, 10);

    setTimeout(function () {
        div.removeClass('mostrar');
        setTimeout(() => div.remove(), 300);
    }, 3000);
}
