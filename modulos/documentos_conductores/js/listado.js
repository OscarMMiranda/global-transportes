// archivo: /modulos/documentos_conductores/js/listado.js

$(document).ready(function () {
    cargarConductores();
});

function cargarConductores() {

    $.ajax({
        url: '/modulos/documentos_conductores/acciones/listar_conductores.php',
        method: 'POST',
        dataType: 'json',

        beforeSend: function () {
            $('#tablaConductores tbody').html(
                '<tr><td colspan="6" class="text-center text-muted py-4">Cargando…</td></tr>'
            );
        },

        success: function (res) {

            if (!res.ok) {
                $('#tablaConductores tbody').html(
                    '<tr><td colspan="6" class="text-center text-danger py-4">Error al cargar</td></tr>'
                );
                return;
            }

            var html = '';

            res.conductores.forEach(function (c) {

                // Estado visual
                var badgeEstado = '';
                if (c.estado === 'Habilitado') {
                    badgeEstado = '<span class="badge bg-success">Habilitado</span>';
                } else {
                    badgeEstado = '<span class="badge bg-danger">Inhabilitado</span>';
                }

                // Por vencer badge
                var badgeVencer = c.docs_por_vencer > 0
                    ? '<span class="badge bg-warning text-dark">' + c.docs_por_vencer + '</span>'
                    : '0';

                html += '<tr>' +
                    '<td>' + c.dni + '</td>' +
                    '<td>' + c.nombre + '</td>' +
                    '<td>' + c.docs_ok + ' / ' + c.docs_total + '</td>' +
                    '<td>' + badgeVencer + '</td>' +
                    '<td>' + badgeEstado + '</td>' +
                    '<td>' +
                        '<a href="/modulos/documentos_conductores/vistas/documentos.php?id=' + c.id + '" ' +
                        'class="btn btn-sm btn-primary">' +
                        '<i class="fas fa-folder-open me-1"></i> Ver documentos</a>' +
                    '</td>' +
                '</tr>';
            });

            $('#tablaConductores tbody').html(html);
        },

        error: function () {
            $('#tablaConductores tbody').html(
                '<tr><td colspan="6" class="text-center text-danger py-4">Error al cargar</td></tr>'
            );
        }
    });
}
