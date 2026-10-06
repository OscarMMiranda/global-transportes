// ======================================================
// JS: ov_modals_ver.js
// RESPONSABILIDAD: Visualizar Orden de Vehículo
// GLOBAL 2026
// ======================================================

function verOV(idOT)
{
    if (!idOT) {
        return;
    }

    console.log('VER OV');
    console.log(idOT);

    $('#modalVerOV').modal('show');

    $('#ovInfoGeneral').html(
        '<div class="text-center p-3">Cargando información...</div>'
    );

    $('#ovConductor').html('-');
    $('#ovTracto').html('-');
    $('#ovRemolque').html('-');

    $('#tablaOVViajes tbody').html(
        '<tr>' +
            '<td colspan="9" class="text-center">' +
                'Cargando viajes...' +
            '</td>' +
        '</tr>'
    );

    $.ajax({

        url: '/modulos/orden_trabajo/api/ov_ver_api.php',

        type: 'POST',

        dataType: 'json',

        data: {
            id_ot: idOT
        },

        success: function(r)
        {
            console.log('RESPUESTA OV');
            console.log(r);

            if (!r.ok) {

                alert(
                    r.msg || 'No fue posible obtener la OV'
                );

                return;
            }

            cargarOVGeneral(
                r.ov || {}
            );

            cargarOVAsignacion(
                r.asignacion || {}
            );

            cargarOVViajes(
                r.viajes || []
            );
        },

        error: function(xhr)
        {
            console.log(xhr);

            alert(
                'Error al cargar la Orden de Vehículo'
            );
        }

    });
}


// ======================================================
// INFORMACIÓN GENERAL
// ======================================================
function cargarOVGeneral(ov)
{
    var html = '';

    html += '<div class="row">';

    html += '<div class="col-md-3">';
    html += '<strong>OV</strong><br>';
    html += (ov.numero_ov || '-');
    html += '</div>';

    html += '<div class="col-md-3">';
    html += '<strong>OT</strong><br>';
    html += (ov.numero_ot || '-');
    html += '</div>';

    html += '<div class="col-md-3">';
    html += '<strong>Cliente</strong><br>';
    html += (ov.cliente || '-');
    html += '</div>';

    html += '<div class="col-md-3">';
    html += '<strong>Tipo OT</strong><br>';
    html += (ov.tipo_ot || '-');
    html += '</div>';

    html += '</div>';

    $('#ovInfoGeneral').html(html);
}


// ======================================================
// ASIGNACION
// ======================================================
function cargarOVAsignacion(asignacion)
{
    $('#ovConductor').text(
        asignacion.conductor || '-'
    );

    $('#ovTracto').text(
        asignacion.tracto || '-'
    );

    $('#ovRemolque').text(
        asignacion.remolque || '-'
    );
}


// ======================================================
// VIAJES
// ======================================================
function cargarOVViajes(viajes)
{
    var tbody = $('#tablaOVViajes tbody');

    tbody.empty();

    if (
        !Array.isArray(viajes) ||
        viajes.length === 0
    ) {

        $('#badgeCantidadOV')
            .text('0 Viajes');

        tbody.html(

            '<tr>' +

                '<td colspan="9" class="text-center text-muted">' +

                    'Sin viajes registrados' +

                '</td>' +

            '</tr>'

        );

        return;
    }

    $('#badgeCantidadOV')
        .text(
            viajes.length +
            (viajes.length === 1
                ? ' Viaje'
                : ' Viajes')
        );

    $.each(viajes, function(i, v){

        tbody.append(

            '<tr>' +

                '<td>' + (v.id || '') + '</td>' +

                '<td>' + (v.fecha || '') + '</td>' +

                '<td>' + (v.numero_ov || '') + '</td>' +

                '<td>' + (v.vehiculo || '') + '</td>' +

                '<td>' + (v.conductor || '') + '</td>' +

                '<td>' + (v.origen || '-') + '</td>' +

                '<td>' + (v.destino || '-') + '</td>' +

                '<td>' + (v.mercaderia || '-') + '</td>' +

                '<td>' + (v.estado || '-') + '</td>' +

            '</tr>'

        );

    });
}