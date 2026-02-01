// ======================================================
//  archivo: /modulos/documentos_conductores/js/documentos.carga.js
//  
//  RESPONSABILIDAD: cargar documentos del conductor
//  SIN handlers duplicados
// ======================================================

console.log("documentos.carga.js cargado correctamente");

// ======================================================
//  FUNCIÓN: cargarDocumentosConductor()
// ======================================================

function cargarDocumentosConductor(idConductor) {

    const tabla = $('#tablaDocumentosConductor tbody');

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
                tabla.html('<tr><td colspan="4" class="text-center text-danger">' + (res.mensaje || 'Error al cargar') + '</td></tr>');
                return;
            }

            if (!res.documentos || res.documentos.length === 0) {
                tabla.html('<tr><td colspan="4" class="text-center text-muted">Sin documentos configurados</td></tr>');
                return;
            }

            res.documentos.forEach(function (doc) {

                let estadoTexto = '🔴 No cargado';
                let clase = 'estado-pendiente';

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

                const fecha = doc.fecha_vencimiento || '—';

                let botones = '';

                if (doc.ruta) {
                    botones +=
                        '<button class="btn btn-sm btn-success me-1 btn-ver-doc" data-ruta="' + doc.ruta + '">Ver</button>' +
                        '<a href="' + doc.ruta + '" download class="btn btn-sm btn-secondary me-1">Descargar</a>' +
                        '<button class="btn btn-sm btn-info me-1 btn-historial" data-tipo="' + doc.tipo_documento_id + '" data-desc="' + escapeHtml(doc.descripcion) + '">Historial</button>' +
                        '<button type="button" class="boton-adjuntar btn btn-sm btn-warning" data-tipo="' + doc.tipo_documento_id + '" data-desc="' + escapeHtml(doc.descripcion) + '">Reemplazar</button>';
                } else {
                    botones +=
                        '<button type="button" class="boton-adjuntar btn btn-sm btn-primary" data-tipo="' + doc.tipo_documento_id + '" data-desc="' + escapeHtml(doc.descripcion) + '">📎 Adjuntar</button>';
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

        error: function () {
            tabla.html('<tr><td colspan="4" class="text-center text-danger">Error al cargar</td></tr>');
        }
    });
}


