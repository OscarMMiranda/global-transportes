// archivo: /modulos/documentos_conductores/js/documentos.js

var idConductorActual = 0;
var tipoDocumentoActual = null;
var descripcionDocumentoActual = '';

$(document).ready(function () {

    idConductorActual = $('#conductor_id').val();
    console.log('conductor_id desde DOM:', idConductorActual);

    cargarDocumentosConductor(idConductorActual);

    $('#btnGuardarDoc').on('click', guardarDocumento);

    // Abrir modal para adjuntar o reemplazar
    $(document).on('click', '.boton-adjuntar', function () {
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
// 1. Cargar documentos del conductor
// ======================================================
function cargarDocumentosConductor(idConductor) {

    var tabla = $('#tablaDocumentosConductor tbody');

    $.ajax({
        url: '/modulos/documentos_conductores/acciones/listar_documentos.php',
        method: 'POST',
        data: { conductor_id: idConductor },
        dataType: 'json',

        beforeSend: function () {
            tabla.html('<tr><td colspan="4" class="text-center text-muted">Cargando…</td></tr>');
        },

        success: function (res) {

            console.log('respuesta listar_documentos:', res);
            tabla.empty();

            if (!res || res.ok !== true) {
                var msg = (res && res.mensaje) ? res.mensaje : 'Error al cargar';
                tabla.html('<tr><td colspan="4" class="text-center text-danger">' + msg + '</td></tr>');
                return;
            }

            if (!res.documentos || res.documentos.length === 0) {
                tabla.html('<tr><td colspan="4" class="text-center text-muted">Sin documentos configurados</td></tr>');
                return;
            }

            $.each(res.documentos, function (i, doc) {

                var estadoTexto = '🔴 No cargado';
                var clase = 'estado-pendiente';

                if (doc.estado === 'OK') {
                    estadoTexto = '🟢 OK';
                    clase = 'estado-ok';
                } else if (doc.estado === 'Vencido') {
                    estadoTexto = '🔴 Vencido';
                    clase = 'estado-vencido';
                } else if (doc.estado === 'PorVencer') {
                    estadoTexto = '🟡 Por vencer';
                    clase = 'estado-vencer';
                }

                var fecha = doc.fecha_vencimiento ? doc.fecha_vencimiento : '—';

                var botones = '';

                if (doc.ruta) {
                    botones +=
                        '<button class="btn btn-sm btn-success me-1 btn-ver-doc" data-ruta="' + doc.ruta + '">Ver</button>' +
                        '<a href="' + doc.ruta + '" download class="btn btn-sm btn-secondary me-1">Descargar</a>' +
                        '<button class="btn btn-sm btn-info me-1 btn-historial" data-tipo="' + doc.tipo_documento_id + '" data-desc="' + escapeHtml(doc.descripcion) + '">Historial</button>' +
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
                    '<td class="' + clase + '">' + estadoTexto + '</td>' +
                    '<td>' + escapeHtml(doc.descripcion) + (doc.obligatorio ? ' <span class="badge bg-danger ms-1">Obligatorio</span>' : '') + '</td>' +
                    '<td>' + fecha + '</td>' +
                    '<td>' + botones + '</td>' +
                    '</tr>'
                );
            });
        },

        error: function (xhr, status, error) {
            console.log("AJAX error:", status, error);
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

        error: function (xhr, status, error) {
            console.log("AJAX error:", status, error);
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

    var cleanRuta = ruta.split('?')[0]; // elimina parámetros
    var ext = cleanRuta.split('.').pop().toLowerCase();

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
// 6. Sanitizar texto
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
// 7. Notificaciones ERP
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
        setTimeout(function () { div.remove(); }, 300);
    }, 3000);
}
