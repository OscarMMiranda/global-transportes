// ======================================================
//  JS: ot_modales_actions.js
//  RESPONSABILIDAD: Acciones del modal de importación OT
//  GLOBAL 2026 — Arquitectura Limpia
// ======================================================   

function procesarImportacionOT() {

    var formData = new FormData($('#formImportarOT')[0]);

    $('#overlay-importar-ot').show();

    $.ajax({
        url: '/modulos/orden_trabajo/api/ot_importar_api.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',

        success: function (r) {
            $('#resultadoImportacionOT').show();
            $('#resultadoImportacionOTDetalle').html(r.mensaje);

            if (r.ok) {
                tablaOT.ajax.reload(null, false);
            }
        },

        complete: function () {
            $('#overlay-importar-ot').hide();
        }
    });
}
