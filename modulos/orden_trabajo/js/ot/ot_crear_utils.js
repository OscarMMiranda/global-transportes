// ======================================================
// JS: ot_crear_utils.js
// RESPONSABILIDAD: Utilidades crear OT
// ======================================================

function cargarFechaActual() {

    var hoy = new Date();

    var yyyy = hoy.getFullYear();
    var mm   = ('0' + (hoy.getMonth() + 1)).slice(-2);
    var dd   = ('0' + hoy.getDate()).slice(-2);

    $('#crear_fecha').val(
        yyyy + '-' + mm + '-' + dd
    );
}

function cargarSiguienteOT() {

    var anio = new Date().getFullYear();

    $.ajax({

        url: '/modulos/orden_trabajo/api/ot_siguiente_numero_api.php',
        type: 'POST',
        dataType: 'json',

        data: {
            anio: anio
        },

        success: function(resp) {

            if (resp.ok) {

                $('#crear_numero_ot')
                    .val(resp.siguiente_ot);

            }

        }

    });
}